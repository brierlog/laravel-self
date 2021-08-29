<?php

namespace App\Logger;

use App\Jobs\ReportToWechatRobot;
use Illuminate\Support\Arr;
use Monolog\Logger;

/**
 * 日志报警钩子
 * Class LogReportHook
 */
class LogReporterHook
{
    public const SIMPLE_FORMAT = '[request_id @ datetime] channel.level_name: message context';

    public const LEVELS = [
        'DEBUG' => Logger::DEBUG,
        'INFO' => Logger::INFO,
        'NOTICE' => Logger::NOTICE,
        'WARNING' => Logger::WARNING,
        'ERROR' => Logger::ERROR,
        'CRITICAL' => Logger::CRITICAL,
        'ALERT' => Logger::ALERT,
        'EMERGENCY' => Logger::EMERGENCY,
    ];

    /**
     * 日志信息
     *
     * @var array
     */
    private $record;

    /**
     * 需报告的日志级别
     *
     * @var string
     */
    private $level;

    /**
     * @var bool|mixed|string
     */
    private $appName;

    public function __construct(array $record)
    {
        $this->record = $record;
        $this->appName = env('APP_NAME');
        $this->level = strtoupper(env('LOG_REPORT_LEVEL'));
    }

    /**
     * 报警处理
     */
    public function process()
    {
        if ($this->isLegalLevel()) {
            $level = $this->getComparableLevel();
            $recordLevel = Arr::get($this->record, 'level', 0);
            if ($recordLevel >= $level) {
                $content = $this->buildContentForReport($this->record);
                ReportToWechatRobot::dispatch($content);
            }
        }
    }

    /**
     * level是否合法
     */
    private function isLegalLevel(): bool
    {
        $levels = self::LEVELS;

        return isset($levels, $this->level);
    }

    /**
     * 将 level 转为可比较的值
     */
    private function getComparableLevel(): int
    {
        return self::LEVELS[$this->level];
    }

    /**
     * 报警日志内容构造
     */
    private function buildContentForReport(array $record): string
    {
        try {
            $search = ['request_id', 'datetime', 'channel', 'level_name', 'message', 'context'];
            $context = json_encode(Arr::get($record, $search[5], []), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $replace = [
                Arr::get($record, $search[0], ''),
                Arr::get($record, $search[1], ''),
                Arr::get($record, $search[2], ''),
                Arr::get($record, $search[3], ''),
                Arr::get($record, $search[4], ''),
                $context,
            ];

            return $this->appName.': '.str_replace($search, $replace, self::SIMPLE_FORMAT);
        } catch (\Exception $e) {
            return '报警日志内容构造异常:'.$e->getMessage();
        }
    }
}
