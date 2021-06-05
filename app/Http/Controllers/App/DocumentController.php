<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\File\UploadFileRequest;
use App\Models\App\Catalogue;
use App\Models\App\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {

        $professional = $request->user()->professional()->first();

        $documents = $professional->documents()->with(['type', 'file'])
            ->whereHas('type',function ($type) use($request){
                $type->where('type',$request->input('type'));
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
        $professional = $request->user();
        $type = Catalogue::getInstance($request->input('type'));
        $document = new Document();
        $document->aditional_information = $request->input('document.aditional_information');
        $document->professional()->associate($professional);
        $document->type()->associate($type);
        $document->save();

        return (new FileController())->upload($request, $document);
    }
}
