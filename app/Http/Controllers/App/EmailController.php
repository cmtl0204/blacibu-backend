<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\Email\EmailSendRequest;
use App\Mail\EmailMailable;
use App\Models\App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(EmailSendRequest $request)
    {
        $files = $request->file('files');
        if (!$files) {
            $files = array();
        }

        $filePaths = array();
        foreach ($files as $file) {
            array_push($filePaths, $file->storeAs('temp_files', $file->getClientOriginalName(), 'public'));
        }

        try {
            if ($request->cc) {
                Mail::to($request->to)
                    ->cc($request->cc)
                    ->send(new EmailMailable(
                        'Notificación de desbloqueo de usuario',
                        $request,
                        $filePaths
                    ));
            } else {
                Mail::to($request->to)
                    ->send(new EmailMailable(
                        $request->subject,
                        $request->body,
                        $filePaths
                    ));
            }
        } catch (\Exception $e) {
            return response()->json([
                'data' => $e->getMessage(),
                'msg' => [
                    'summary' => 'Error',
                    'detail' => '',
                    'code' => '400'
                ]], 400);
        } finally {
            foreach ($filePaths as $filePath) {
                Storage::delete('public/' . $filePath);
            }
        }

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Success',
                'detail' => '',
                'code' => '200'
            ]], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\App\Setting $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
