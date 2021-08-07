<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/4 10:10 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Library\WeChat\MiniProgram;
use App\Models\Metting;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;

class SigningService
{

    /**
     * @param $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function lists($params): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $limit = arr_value($params, 'per_page', 10);
        $user_id = user_id();
        $query = Metting::query();
        $query->where(['mettings.created_by' => $user_id, 'mettings.is_need_sign' => 1]);
        $columns = ['mettings.id', 'mettings.room_id', 'mettings.subject', 'rooms.name', 'mettings.metting_start_time'];
        $query->leftJoin('rooms', 'rooms.id', '=', 'mettings.room_id');
        return $query->paginate($limit, $columns);
    }

    /**
     * @param $params
     * @return array
     */
    #[ArrayShape(['url' => "string"])] public function code($params): array
    {
        $query = Metting::query();
        $query->where(['id' => arr_value($params, 'metting_id/d', 0)]);
        $metting_id =  $query->first(['id', 'room_id'])->id;
        $metting_code = [
            'type' => 'signing',
            'metting_id' => $metting_id,
        ];
        // 加密
        $scene = http_build_query($metting_code);
        $optional = [
            'page' => 'pages/sign/signDetails/index'
        ];
        // 生成小程序码
        $mini_program = new MiniProgram();
        $app_code = $mini_program->appCode($scene, $optional);
        $storage = Storage::disk('local');
        $storage->put(md5($scene).'.jpeg', $app_code);
        return ['url' => $storage->url(md5($scene).'.jpeg')];
    }

    public function share($params)
    {

    }

    public function statistics($params)
    {

    }
}
