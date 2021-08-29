<?php

namespace App\Logger;

use Monolog\Formatter\LineFormatter as LogLineFormatter;

class LineFormatter extends LogLineFormatter
{
    public const SIMPLE_FORMAT = " @ %datetime%] %channel%.%level_name%: %message% %context% %extra%\n";

    /**
     * 打印到文件的日志格式
     * eg: [req-5febe26684230 @ 2020-12-30 10:13:58] dev.INFO: test {"exception":"App\\Exceptions\\BusinessException","file":"/Users/jeff/Code/mall_admin/app/Http/Controllers/Orders/OrdersController.php","line":283} []
     */
    public function format(array $record): string
    {
        $record['datetime'] = $record['datetime']->format('Y-m-d H:i:s');
        // X_REQUEST_ID 唯一请求ID，在 admin_operation_log 表或 Kibana(生产) 中可以查询
        $record = array_merge(['request_id' => X_REQUEST_ID], $record);

        // 发送报警消息
        if (env('LOG_REPORT_ENABLE')) {
            (new LogReporterHook($record))->process();
        }

        // X_REQUEST_ID 唯一请求ID，在 admin_operation_log 表或 Kibana(生产) 中可以查询
        return '['.X_REQUEST_ID.parent::format($record);
    }
}
