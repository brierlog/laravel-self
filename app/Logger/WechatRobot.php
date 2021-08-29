<?php

namespace App\Logger;

use GuzzleHttp\Client;

class WechatRobot
{
    /**
     * @var string 机器人的唯一key
     */
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function send(string $content)
    {
        $this->requestText($content);
    }

    /**
     * 向企业微信群机器人发送文本消息
     *
     * @param $content
     */
    private function requestText(string $content)
    {
        $this->request(
            [
                'msgtype' => 'text',
                'text' => [
                    'content' => substr($content, 0, 1024), // 文本内容，微信限制最长不超过2048个字节
                ],
            ]
        );
    }

    private function request(array $body)
    {
        try {
            $client = new Client(
                [
                    'base_uri' => 'https://qyapi.weixin.qq.com',
                    'timeout' => 0.5, // 超时时间1秒
                ]
            );
            $client->post(
                "cgi-bin/webhook/send?key={$this->key}",
                [
                    'json' => $body,
                ]
            );
        } catch (\Exception $e) {
            return;
        }
    }
}
