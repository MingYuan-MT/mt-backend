<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    /**
     * 登陆&注册
     * @param $data
     * @return string
     */
    public static function login($data): string
    {
        $api_token = rand_str();
        self::query()->updateOrCreate([
            'mobile' => arr_value($data, 'mobile'),
        ], [
            'name' => arr_value($data, 'nick_name', ''),
            'unionid' => arr_value($data, 'unionid', ''),
            'api_token' => $api_token,
            'created_by' => arr_value($data, 'created_by', ''),
            'modified_by' => arr_value($data, 'modified_by', ''),
        ]);
        return $api_token;
    }
}
