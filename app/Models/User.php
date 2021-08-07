<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];
    protected $table = 'users';
    protected $fillable = ['name','id_number','mobile','seat_number','openid','api_token','is_deleted','update_by','status','created_by'];

    /**
     * 登陆&注册
     * @param $data
     * @return string
     */
    public static function login($data): string
    {
        $api_token = rand_str();
        self::query()->updateOrCreate([
            'openid' => arr_value($data, 'mobile'),
        ], [
            'name' => arr_value($data, 'nick_name', ''),
            'api_token' => $api_token,
            'created_by' => arr_value($data, 'created_by', ''),
            'modified_by' => arr_value($data, 'modified_by', ''),
        ]);
        return $api_token;
    }

    public static function info($condition,$fileds="*")
    {
        $data = self::query()->where($condition)->get($fileds)->first();
        return json_decode(json_encode(collect($data)->toArray(),true),true);;
    }
}
