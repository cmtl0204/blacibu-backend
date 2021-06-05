<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\File\UploadFileRequest;
use App\Models\App\File;
use App\Models\App\Image;
use App\Models\App\Payment;
use App\Models\App\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfessionalController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function getProfessional(Request $request)
    {
        $professional = $request->user()->professional()->first();

        return response()->json([
            'data' => $professional,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    public function getPayments(Request $request)
    {
        $professional = $request->user()->professional()->first();

        $payments = $professional->payments()->with('file')->get();

        return response()->json([
            'data' => $payments,
            'msg' => [
                'summary' => 'success',
                'detail' => '',
                'code' => '200',
            ]], 200);
    }

    function uploadPaymentsFiles(UploadFileRequest $request)
    {
        $paymentData = json_decode($request->input('payment'));
        $professional = $request->user();
        if ($paymentData->file) {
            File::destroy($paymentData->file->id);
            Storage::delete($paymentData->file->full_path);
        }

        $payment = $paymentData->id ? Payment::find($paymentData->id) : new Payment();
        $payment->bank = $paymentData->bank;
        $payment->date = $paymentData->date;
        $payment->transfer_number = $paymentData->transfer_number;
        $payment->professional()->associate($professional);
        $payment->save();

        return (new FileController())->upload($request, $payment);
    }

    function updatePayment(Request $request)
    {
        $payment =  Payment::find($request->input('payment.id'));
        $payment->bank = $request->input('payment.bank');
        $payment->date = $request->input('payment.date');
        $payment->transfer_number = $request->input('payment.transfer_number');
        $payment->save();

        return response()->json([
            'data' => null,
            'msg' => [
                'summary' => 'Pago Actualizado',
                'detail' => 'Se actualizó correctamente',
                'code' => '201'
            ]], 201);
    }
}
