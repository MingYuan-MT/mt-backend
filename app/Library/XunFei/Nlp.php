<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 2:08 上午
 * @Author      : Jade
 */

namespace App\Library\XunFei;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Nlp
{
    private mixed $app_id;

    private mixed $app_key;

    private mixed $client;

    private array $headers;

    private string $host = 'http://ltpapi.xfyun.cn/v1';

    private string $cws_api = '/cws'; // 中文分词

    private string $pos_api = '/pos'; // 词性标注

    private string $ner_api = '/ner'; // 命名实体识别

    private string $dp_api = '/dp'; // 依存句法分析

    private string $srl_api = '/srl'; // 语义角色标注

    private string $sdp_api = '/sdp'; // 语义依存 (依存树) 分析

    private string $sdgp_api = '/sdgp'; // 语义依存 (依存图) 分析

    public function __construct()
    {
        $this->app_id = config('nlp.app_id');
        $this->app_key = config('rtc.api_key');
        if (empty($this->app_id) || empty($this->app_key)) {
            throw new HttpException(500, 'NLP服务配置不正常，请检查配置');
        }
        $this->client = new Http();
        $this->sign();
    }

    public function get()
    {
        $text = '帮我预定明天下午两点半的摩天崖会议室';
        $word = $this->request($this->cws_api, $text);
        $pos = $this->request($this->pos_api, $text);
        $data = $row = [];
        foreach ($word as $key => $value) {
            $data[] = [
                'text' => $value,
                'type' => $pos[$key],
            ];
        }

        foreach ($data as $value) {
            $row[$value['type']][] = $value['text'];
        }
        dd($row);
    }

    private function request($api, $text)
    {
        $data = [
            'text' => $text,
        ];
        $url = $this->host . $api;
        $result = $this->client::timeout(15*60)->withHeaders($this->headers)->post($url, $data);
        $row = json_decode($result, true);
        return array_shift($row['data']);
    }

    private function sign()
    {
        $param = ['type' => 'dependent'];
        $time = time();
        $x_param = base64_encode(json_encode($param));
        $x_check_sum = md5($this->app_key . $time . $x_param);
        $this->headers = [
            'X-CurTime' => $time,
            'X-Param' => $x_param,
            'X-Appid' => $this->app_id,
            'X-CheckSum' => $x_check_sum,
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
        ];
    }

}
