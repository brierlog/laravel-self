<?php

namespace App\Logger;

use Monolog\Formatter\JsonFormatter as LogJsonFormatter;

class JsonFormatter extends LogJsonFormatter
{
    public function format(array $record): string
    {
        $record['datetime'] = $record['datetime']->format('Y-m-d H:i:s');
        // X_REQUEST_ID 唯一请求ID，在 admin_operation_log 表或 Kibana(生产) 中可以查询
        $record = array_merge(['request_id' => X_REQUEST_ID], $record);

        // 发送报警消息
        if (env('LOG_REPORT_ENABLE')) {
            (new LogReporterHook($record))->process();
        }

        return $this->toJson($this->normalize($record), true).($this->appendNewline ? "\n" : '');
    }
}
