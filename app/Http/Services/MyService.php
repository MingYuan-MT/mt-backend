<?php
/**
 * @Description :
 *
 * @Date        : 2021/8/7 4:32 上午
 * @Author      : Jade
 */

namespace App\Http\Services;

use App\Models\ReserveRecord;

class MyService
{
    public function records()
    {
        return ReserveRecord::records(['user_id' => user_id()]);
    }
}
