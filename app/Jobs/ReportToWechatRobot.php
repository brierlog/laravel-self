<?php

namespace App\Jobs;

use App\Logger\WechatRobot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReportToWechatRobot implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * 任务可尝试的次数
     *
     * @var int
     */
    public $tries = 1;

    public $timeout = 2; // 2s

    private $reporter;

    private $content;

    /**
     * Create a new job instance.
     */
    public function __construct(string $content)
    {
        $this->content = $content;
        $this->reporter = new WechatRobot(env('LOG_REPORT_KEY'));
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->reporter->send($this->content);
    }
}
