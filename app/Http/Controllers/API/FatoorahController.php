<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\services\FatoorahServices;

class FatoorahController extends Controller
{
    private $fatoorahServivce;
    public function __construct(FatoorahServices $fatoorahServivce)
    {
        $this->fatoorahServivce = $fatoorahServivce; //l varibale ely fo2 b2a fe l injected class


    }

    public function payOrder(Request $request)
    {


        //to return invoice link from my fatrooh the keys must be written as shown..the user will pay be this link outside
        //data from database by select

        $data = [
            "CustomerName" => $request->input('CustomerName'),
            "NotificationOption" => "LNK",
            "InvoiceValue" => $request->input('InvoiceValue'),
            "CustomerEmail" => $request->input('CustomerEmail'),
            "CallBackUrl" => 'http://127.0.0.1:8000/api/call_back',
            "ErrorUrl" => 'https://google.com',
            "Language" => 'en',
            "DisplayCurrencyIso" => 'SAR'
        ];
        return  $this->fatoorahServivce->sendPayment($data);
        //transaction table needed in database to store the values  $invoice,to know user
        //$invoiceid=1262025;
        //$userid =auth()-> id() ; //id of user who pay

    }
    public function paymentCallBack(Request $request)
    {
        //save the transaction to database //you must change order status from pending to  paid

        $data = [];
        $data['Key'] = $request->paymentId;
        $data['KeyType'] = 'paymentId';
        //return  $this->fatoorahServivce->getPaymentStatus($data);
        $paymentData = $this->fatoorahServivce->getPaymentStatus($data);
        return  $paymentData['Data']['InvoiceId'];
        //in database search with invoice id to get the customer
    }
}
