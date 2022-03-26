<?php 

namespace App\services;
//use App\Http\Services\Request;
//use Http\Client ;
use GuzzleHttp\Client ;
use GuzzleHttp\Psr7\Request;
class FatoorahServices {
    private $base_url ;
    private $headers ;
    private $request_client;

    public function __construct(Client $request_client){
        $this->request_client=$request_client;
        $this->base_url=env(key: 'fatoorah_base_url');
        $this->headers=[
  'Content-Type' => 'Application/json' ,
  'authorization' =>'Bearer '. env(key:'fatoorah_token')
        ];

    }
//establish connection
    private function buildRequest ($uri, $method ,$data =[]){
        //url as v2/sendpayment from mayfatoorah
        $request= new Request ($method , $this->base_url.$uri ,$this->headers);
        //to call serve you must have data
        if(!$data)
        return false ;
        $response=$this->request_client->send($request,[
            "json"=>$data

        ]);
        if($response->getStatusCode() != 200){
        return false ;}

        $response=json_decode($response->getBody(),associative:true);
        return $response ;



    }
    public function sendPayment($data){

       $response=$this ->buildRequest('/v2/SendPayment','Post',$data);
       return $response;
    }

    public function getPaymentStatus($data){
       return $response=$this ->buildRequest('/v2/getPaymentStatus','Post',$data);
    }
    
}