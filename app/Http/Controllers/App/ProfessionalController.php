<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\File\UploadFileRequest;
use App\Models\App\Address;
use App\Models\App\Catalogue;
use App\Models\App\File;
use App\Models\App\Location;
use App\Models\App\Payment;
use App\Models\App\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfessionalController extends Controller
{
    public function index()
    {
        //
    }

    public function updateProfessional(Request $request)
    {
        $language = Catalogue::find($request->input('professional.language.id'));
        $identificationType = Catalogue::find($request->input('professional.user.identification_type.id'));
        $country = Location::find($request->input('professional.country.id'));

        $user = $request->user();
        $user->username = $request->input('professional.user.identification');
        $user->identification = $request->input('professional.user.identification');
        $user->name = $request->input('professional.user.name');
        $user->lastname = $request->input('professional.user.lastname');
        $user->email = $request->input('professional.user.email');
        $user->birthdate = $request->input('professional.user.birthdate');
        $user->phone = $request->input('professional.user.phone');
        $user->language()->associate($language);
        $user->identificationType()->associate($identificationType);

        if ($request->input('professional.user.address')) {
            $address = $user->address()->first() ? $user->address()->first() : new Address();
            $location = Location::find($request->input('professional.user.address.location.id'));
            $address->main_street = $request->input('professional.user.address.main_street');
            $address->secondary_street = $request->input('professional.user.address.secondary_street');
            $address->post_code = $request->input('professional.user.address.post_code');
            $address->location()->associate($location);
            $address->save();
            $user->address()->associate($address);
        }

        $professional = $user->professional()->first();
        $professional->membership_number = $request->input('professional.membership_number');
        $professional->certified_date = $request->input('professional.certified_date');
        $professional->degree_time = $request->input('professional.degree_time');
        $professional->years_graduated = $request->input('professional.years_graduated');
        $professional->nationality = $request->input('professional.nationality');
        $professional->user()->associate($user);
        $professional->country()->associate($country);

        if ($request->input('professional.socialmedia')) {
            $professional->socialmedia()->detach();
            foreach ($request->input('professional.socialmedia') as $socialmedia) {
                $professional->socialmedia()->attach($socialmedia['socialmedia']['id'],
                    ['user' => $socialmedia['user']]);
            }

        }

//        DB::transaction(function () use ($user, $professional, $address) {
        $user->save();
        $professional->save();
//        });

        return response()->json([
            'data' => $professional,
            'msg' => [
                'summary' => 'Sus datos fueron actualizados',
                'detail' => '',
                'code' => '201'
            ]
        ], 201);
    }

    public function store(Request $request)
    {
        //
    }

    public function getProfessional(Request $request)
    {
        $professional = $request->user()->professional()->with(['country', 'status', 'socialmedia'])->with(['user' => function ($user) {
            $user->with(['identificationType', 'language'])->with(['address' => function ($address) {
                $address->with('location');
            }]);
        }])->first();

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

        $payments = $professional->payments()->with(['file', 'status'])->get();

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
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $status = Status::firstWhere('code', $catalogues['status']['in_revision']);
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
        $payment->status()->associate($status);
        $payment->professional()->associate($professional);
        $payment->save();

        return (new FileController())->upload($request, $payment);
    }

    function updatePayment(Request $request)
    {
        $professional = $request->user()->professional()->first();
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $status = Status::firstWhere('code', $catalogues['status']['in_revision']);
        $payment = empty($request->input('payment.id')) ? new Payment() : Payment::find($request->input('payment.id'));
        $payment->bank = $request->input('payment.bank');
        $payment->date = $request->input('payment.date');
        $payment->transfer_number = $request->input('payment.transfer_number');
        $payment->status()->associate($status);
        $payment->professional()->associate($professional);
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
