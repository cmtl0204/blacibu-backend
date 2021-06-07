<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\App\Location;
use App\Models\App\SocialMedia;
use Illuminate\Http\Request;
use App\Models\App\Catalogue;

class SocialmediaController extends Controller
{
    public function index()
    {
        $socialMedia = SocialMedia::get();
        return response()->json([
            'data' => $socialMedia,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }


}
