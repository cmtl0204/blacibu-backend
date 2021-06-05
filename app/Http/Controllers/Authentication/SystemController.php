<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\Authentication\System;

class SystemController extends Controller
{
    public function show(System $system)
    {
        return response()->json([
            'data' => $system,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }
}
