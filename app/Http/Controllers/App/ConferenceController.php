<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\File\UploadFileRequest;
use App\Models\App\Catalogue;
use App\Models\App\Conference;
use App\Models\App\Status;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index(Request $request)
    {
        $professional = $request->user()->professional()->first();

        $conferences = Catalogue::whereHas('conferences', function ($conferences) use ($request) {
            $conferences->whereHas('type', function ($type) use ($request) {
                $type->where('type', $request->input('type'))->orderBy('code');
            });
        })
            ->with(['conferences' => function ($conferences) use ($professional, $request) {
                $conferences->with(['file','status'])
                    ->where('professional_id', $professional->id);
            }])
            ->get();
        return response()->json([
            'data' => $conferences,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    function delete(Request $request)
    {
        // Es una eliminación lógica
        Conference::destroy($request->input('ids'));

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Conferencia eliminada',
                'detail' => 'Se eliminó correctamente',
                'code' => '201'
            ]], 201);
    }

    function uploadFiles(UploadFileRequest $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $conferenceData = json_decode($request->input('conference'));
        $professional = $request->user();
        $type = Catalogue::getInstance($request->input('type'));
        $status = Status::firstWhere('code', $catalogues['status']['in_revision']);

        $conference = new Conference();
        $conference->modality = $conferenceData->modality;
        $conference->name = $conferenceData->name;
        $conference->category = $conferenceData->category;
        $conference->postition = $conferenceData->postition;
        $conference->years = $conferenceData->years;
        $conference->function = $conferenceData->function;
        $conference->indexed_journal = $conferenceData->indexed_journal;
        $conference->association = $conferenceData->association;
        $conference->event = $conferenceData->event;
        $conference->professional()->associate($professional);
        $conference->type()->associate($type);
        $conference->status()->associate($status);
        $conference->save();

        return (new FileController())->upload($request, $conference);
    }

    function update(Request $request)
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $status = Status::firstWhere('code', $catalogues['status']['in_revision']);
        $conference =  Conference::find($request->input('conference.id'));
        $conference->modality = $request->input('conference.modality');
        $conference->name = $request->input('conference.name');
        $conference->category = $request->input('conference.category');
        $conference->postition = $request->input('conference.postition');
        $conference->years = $request->input('conference.years');
        $conference->indexed_journal =$request->input('conference.indexed_journal');
        $conference->association =$request->input('conference.association');
        $conference->event = $request->input('conference.event');
        $conference->status()->associate($status);
        $conference->save();

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Conferencia Actualizada',
                'detail' => 'Se actualizó correctamente',
                'code' => '201'
            ]], 201);
    }
}
