<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * @mixin \Eloquent
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Illuminate\Database\Query\Builder
 */
class Model extends EloquentModel
{
    /**
     * 自定义内容分页程序
     *
     * @param $itemsTransformed
     *
     * @return LengthAwarePaginator
     */
    public function cusPaginator($itemsTransformed, LengthAwarePaginator $items)
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $itemsTransformed,
            $items->total(),
            $items->perPage(),
            $items->currentPage(),
            [
                'path' => '',
                'query' => [
                    'page' => $items->currentPage(),
                ],
            ]
        );
    }

    /**
     * 批量更新
     *
     * @param array $multipleData
     *
     * @return bool|int
     */
    public function updateBatch($multipleData = [])
    {
        try {
            if (empty($multipleData)) {
                throw new \Exception('数据不能为空');
            }
            $tableName = DB::connection()->getTablePrefix().$this->getTable(); // 表名
            $firstRow = current($multipleData);

            $updateColumn = array_keys($firstRow);
            // 默认以id为条件更新，如果没有ID则以第一个字段为条件
            $referenceColumn = isset($firstRow['id']) ? 'id' : current($updateColumn);
            unset($updateColumn[0]);
            // 拼接sql语句
            $updateSql = 'UPDATE '.$tableName.' SET ';
            $sets = [];
            $bindings = [];
            foreach ($updateColumn as $uColumn) {
                $setSql = '`'.$uColumn.'` = CASE ';
                foreach ($multipleData as $data) {
                    $setSql .= 'WHEN `'.$referenceColumn.'` = ? THEN ? ';
                    $bindings[] = $data[$referenceColumn];
                    $bindings[] = $data[$uColumn];
                }
                $setSql .= 'ELSE `'.$uColumn.'` END ';
                $sets[] = $setSql;
            }
            $updateSql .= implode(', ', $sets);
            $whereIn = collect($multipleData)->pluck($referenceColumn)->values()->all();
            $bindings = array_merge($bindings, $whereIn);
            $whereIn = rtrim(str_repeat('?,', count($whereIn)), ',');
            $updateSql = rtrim($updateSql, ', ').' WHERE `'.$referenceColumn.'` IN ('.$whereIn.')';
            // 传入预处理sql语句和对应绑定数据
            return DB::update($updateSql, $bindings);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 此方法为Eloquent模型 关联关系补充WhereHasIn
     * 若使用WhereHas时，主表的数据过大导致查询效率降低，可该用此方法
     * @param $relationName
     * @throws \Exception
     * @return object
     */
    public function scopeWhereHasIn(Builder $builder, $relationName, callable $callable)
    {
        $relationNames = explode('.', $relationName);
        $nextRelation = implode('.', array_slice($relationNames, 1));

        $method = $relationNames[0];

        $relation = Relation::noConstraints(
            function () use ($method) {
                return $this->{$method}();
            }
        );

        if ($nextRelation) {
            $in = $relation->getQuery()->whereHasIn($nextRelation, $callable);
        } else {
            $in = $relation->getQuery()->where($callable);
        }

        if ($relation instanceof BelongsTo) {
            return $builder->whereIn($relation->getForeignKey(), $in->select($relation->getOwnerKey()));
        }
        if ($relation instanceof HasOne) {
            return $builder->whereIn($this->getKeyName(), $in->select($relation->getForeignKeyName()));
        }
        if ($relation instanceof HasMany) {
            return $builder->whereIn($this->getKeyName(), $in->select($relation->getForeignKeyName()));
        }

        throw new \Exception(__METHOD__.' 不支持 '.get_class($relation));
    }
}
