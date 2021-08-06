<?php

namespace App\Http\Controllers\MiniApi;

use App\Http\Services\RoomService;

class RoomController extends CommonController
{

    public function list(RoomService $service)
    {
        $params = $this->request->all();
        return $service->list($params);
    }
}
