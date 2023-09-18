<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\File\UploadFileRequest;
use App\Models\App\Catalogue;
use App\Models\App\Document;
use App\Models\App\Status;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {

        $professional = $request->user()->professional()->first();

        $documents = $professional->documents()->with(['type', 'file','status'])
            ->whereHas('type', function ($type) use ($request) {
                $type->where('type', $request->input('type'));
            })
            ->get();

        return response()->json([
            'data' => $documents,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    function delete(Request $request)
    {
        // Es una eliminación lógica
        Document::destroy($request->input('ids'));

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Documento eliminado',
                'detail' => 'Se eliminó correctamente',
                'code' => '201'
            ]], 201);
    }

    function uploadFiles(UploadFileRequest $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $professional = $request->user()->professional()->first();
        $type = Catalogue::find($request->input('type'));
        $status = Status::firstWhere('code', $catalogues['status']['in_revision']);
        $document = new Document();
//        $document->additional_information = $request->input('additional_information')!='undefined'? $request->input('additional_information'):'';
        $document->professional()->associate($professional);
        $document->type()->associate($type);
        $document->status()->associate($status);
        $document->save();

        return (new FileController())->upload($request, $document);
    }
}
