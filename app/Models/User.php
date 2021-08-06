<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    /**
     * @param $data
     * @return string
     */
    public static function login($data): string
    {
        $user = self::query()->updateOrCreate([
            'mobile' => arr_value($data, 'mobile'),
        ], [
            'name' => arr_value($data, 'nick_name', ''),
            'unionid' => arr_value($data, 'unionid', ''),
            'created_by' => arr_value($data, 'created_by', ''),
            'modified_by' => arr_value($data, 'modified_by', ''),
        ]);
        $api_token = rand_str();
        $user->api_token = $api_token;
        $user->save();
        return $api_token;
    }
}
