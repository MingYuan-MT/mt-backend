<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 9:22 上午
 * @Author      : Jade
 */

namespace App\Library\UnitTime;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UnitTime
{
    private string $host;

    private mixed $client;

    private string $api = '/unit/time';

    /**
     *
     */
    public function __construct()
    {
        $this->host = config('unittime.host');
        if (empty($this->host)) {
            throw new HttpException(500, '时间处理模块服务配置不正常，请检查配置');
        }
        $this->client = new Http();
    }

    /**
     * @param $text
     * @return mixed
     */
    public function get($text): mixed
    {
        $data = $this->request($this->api, $text);
        $row = [];
        if (!empty($data)) {
            $row = array_shift($data);
        }
        return $row;
    }

    /**
     * @param $api
     * @param $text
     * @return mixed
     */
    private function request($api, $text): mixed
    {
        $data = [
            'current_time' => Carbon::now()->format('Y-n-j H') . ':00:00',
            'data' => $text,
        ];
        $url = $this->host . $api;
        $response = $this->client::get($url, $data)->collect();
        $result = $response->toArray();

        $row = [];
        if ($result['code'] == 200 && !empty($result['result'])) {
            $row = arr_value($result, 'result/a', []);
        }
        return $row;
    }
}
