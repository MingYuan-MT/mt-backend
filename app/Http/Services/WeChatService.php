<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 5:16 下午
 * @Author      : Jade
 */

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;

class WeChatService
{
    public function code($params)
    {
        $file = arr_value($params, 'name/s', '');
        try {
            $res = Storage::disk('local')->get($file);
            $meta = Storage::disk('local')->getMimetype($file);
            $size = Storage::disk('local')->size($file);
            return response($res, 200, [
                'Content-Type' => $meta,
                'Content-Length' => $size,
                'Content-Disposition' => 'inline; filename="'. basename($file) .'"',
            ]);
        } catch (\Exception $e) {
            client_error('File Not Found' . $e->getMessage());
        }
    }
}
