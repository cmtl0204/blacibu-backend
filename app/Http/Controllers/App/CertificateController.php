<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\File\UploadFileRequest;
use App\Models\App\Catalogue;
use App\Models\App\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $professional = $request->user()->professional()->first();

        $certificates = Catalogue::whereHas('certificates', function ($certificates) use ($request) {
            $certificates->whereHas('type', function ($type) use ($request) {
                $type->where('type', $request->input('type'))->orderBy('code');
            });
        })
            ->with(['certificates' => function ($certificates) use ($professional, $request) {
                $certificates->with(['file'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();
        return response()->json([
            'data' => $certificates,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    function delete(Request $request)
    {
        // Es una eliminación lógica
        Certificate::destroy($request->input('ids'));

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Certificado eliminado',
                'detail' => 'Se eliminó correctamente',
                'code' => '201'
            ]], 201);
    }

    function uploadFiles(UploadFileRequest $request)
    {
        $certificateData = json_decode($request->input('certificate'));
        $professional = $request->user();
        $type = Catalogue::getInstance($request->input('type'));

        $certificate = new Certificate();
        $certificate->modality = $certificateData->modality;
        $certificate->name = $certificateData->name;
        $certificate->hours = $certificateData->hours;
        $certificate->postition = $certificateData->postition;
        $certificate->years = $certificateData->years;
        $certificate->institution_endorse = $certificateData->institution_endorse;
        $certificate->indexed_journal = $certificateData->indexed_journal;
        $certificate->in_quality = $certificateData->in_quality;
        $certificate->professional()->associate($professional);
        $certificate->type()->associate($type);
        $certificate->save();

        return (new FileController())->upload($request, $certificate);
    }

    function update(Request $request)
    {
        $certificate =  Certificate::find($request->input('certificate.id'));
        $certificate->modality = $request->input('certificate.modality');
        $certificate->name = $request->input('certificate.name');
        $certificate->hours = $request->input('certificate.hours');
        $certificate->postition = $request->input('certificate.postition');
        $certificate->years = $request->input('certificate.years');
        $certificate->institution_endorse =$request->input('certificate.institution_endorse');
        $certificate->indexed_journal =$request->input('certificate.indexed_journal');
        $certificate->in_quality = $request->input('certificate.in_quality');
        $certificate->save();

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Certificado Actualizado',
                'detail' => 'Se actualizó correctamente',
                'code' => '201'
            ]], 201);
    }
}
