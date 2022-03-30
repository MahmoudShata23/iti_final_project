<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\services\FatoorahServices;
use App\Models\Order;
use App\Models\User;

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
            "CustomerMobile" => $request->input('CustomerMobile'),
            'MobileCountryCode'  => '+2',
            "CallBackUrl" => 'http://127.0.0.1:8000/api/call_back',
            "ErrorUrl" => 'https://google.com',
            "Language" => 'en',
            "DisplayCurrencyIso" => 'EGP'

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
        $user = User::where(['email' => $paymentData['Data']['CustomerEmail']])->first();



        $orderObject = new Order;
        $orderObject->name = $user->name;
        $orderObject->email = $user->email;
        $orderObject->phone = $user->phone;
        $orderObject->address = $user->address . ", " . $user->city . ", " . $user->region;
        $orderObject->payment_type = $paymentData['Data']['InvoiceTransactions']['0']['PaymentGateway'];
        $orderObject->currency = $paymentData['Data']['InvoiceTransactions']['0']['Currency'];
        $orderObject->amount = floatval(explode(" ", str_replace(array(','), '', $paymentData['Data']['InvoiceDisplayValue']))[0]);
        $orderObject->invoice_number = $paymentData['Data']['InvoiceId'];
        $orderObject->order_date = now()->day;
        $orderObject->order_month = now()->month;
        $orderObject->order_year = now()->year;
        $orderObject->save();

        return  redirect('http://localhost:4200/user-orders');
        //in database search with invoice id to get the customer

    }

    public function CashOrder(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'InvoiceValue' => 'required|numeric'
        ]);
        $user = User::where(['email' => $request->email])->first();

        $orderObject = new Order;
        $orderObject->name = $user->name;
        $orderObject->email = $user->email;
        $orderObject->phone = $user->phone;
        $orderObject->address = $user->address . ", " . $user->city . ", " . $user->region;
        $orderObject->payment_type = "Cash On Delivery";
        $orderObject->currency = "EGP";
        $orderObject->amount = $request->InvoiceValue;
        $orderObject->invoice_number = 0;
        $orderObject->order_date = now()->day;
        $orderObject->order_month = now()->month;
        $orderObject->order_year = now()->year;
        $orderObject->save();

        return response()->json([
            'status' => 200,
            'message' => 'order added successfully'
        ]);
    }

    public function UserOrder($email)
    {
        $matchQuery = ['email' => $email];

        $orders = Order::where($matchQuery)->get();

        if (count($orders) > 0) {
            return response()->json([
                'status' => 200,
                'orders' => $orders
            ]);
        } else {
            return response()->json([
                'status' => 422,
                'orders' => 'there are no orders'
            ]);
        }
    }

    public function DeleteUserOrder($id)
    {
        $deleted = Order::where(['id' => $id])->first();
        if ($deleted) {
            $deleted->delete();
            return response()->json([
                'status' => 200,
                'message' => 'deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'id not found'
            ]);
        }
    }
}
