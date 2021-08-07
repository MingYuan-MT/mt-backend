<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/4 10:10 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Library\WeChat\MiniProgram;
use App\Models\Joiner;
use App\Models\Metting;
use App\Models\User;
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
        $data = $query->paginate($limit, $columns);
        $week_array = ['日', '一', '二', '三', '四', '五', '六'];
        $data->each(function ($item) use ($week_array) {
            $item->week = '星期' . $week_array[date('w', strtotime($item->metting_start_time))];
        });
        return $data;
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
        $file_name = md5($scene).'.jpeg';
        $storage = Storage::disk('local');
        $storage->put($file_name, $app_code);
        return ['url' => $storage->url($file_name)];
    }

    /**
     * @param $params
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function signing($params): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $metting_id = arr_value($params, 'metting_id/d', 0);
        $user = User::info();
        $user_id = arr_value($user, 'id/d', 0);
        $metting_info = Metting::query()->where(['id' => $metting_id])->first(['moderator_id']);
        $type = 2;
        if ($metting_info->moderator_id == $user_id) {
            $type = 1;
        }
        $joiner_query = Joiner::query();
        return $joiner_query->updateOrCreate([
            'metting_id' => $metting_id,
            'user_id' => $user_id,
        ], [
            'user_name' => arr_value($user, 'name', ''),
            'type' => $type,
            'status' => 1,
            'created_by' => $user_id,
            'modified_by' => $user_id,
        ]);
    }

    /**
     * @param $params
     * @return array
     */
    #[ArrayShape(['count' => "int", 'yes' => "array", 'no' => "array"])] public function statistics($params): array
    {
        $metting_id = arr_value($params, 'metting_id/d', 0);
        $joiner_query = Joiner::query();
        $columns = ['users.name', 'users.avatar', 'joiners.status'];
        $data = $joiner_query->where(['metting_id' => $metting_id])
            ->leftJoin('users', 'users.id', '=', 'joiners.user_id')
            ->get($columns);
        $data = collect($data)->toArray();
        $yes = $no = [];
        foreach ($data as $item) {
            if ($item['status']) {
                $yes[] = $item;
            } else {
                $no[] = $item;
            }
        }
        return [
            'count' => count($yes),
            'yes' => $yes,
            'no' => $no
        ];
    }
}
