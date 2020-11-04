<?php

namespace App\Http\Controllers;

use App\Repositories\LoginRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;

class LoginController extends Controller
{
    private $login;

    public function __construct(LoginRepositoryInterface  $login)
    {
        $this->login = $login;
    }

    public function checkAuthentication(Request $request)
    {
        $messages = [
            'required' => 'The :attribute field is required.',
        ];

        //ตรวจสอบข้อมูล
        $validator =  Validator::make($request->all(), [
            'auth_code' => 'required'
        ], $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 500);
        }

        $data = $request->all();

        $auth_code = $data['auth_code'];
        $cliend_id = env('CLIENT_ID');
        $secrete_id = env('SECRETE_ID');
        $redirect_uri = env('REDIRECT_URI');
        $response = null;

        try {
            $URL = env('SSO_URL') . "/oauth/token?client_secret=$secrete_id&client_id=$cliend_id&code=$auth_code&redirect_uri=$redirect_uri";
            $client = new Client(['base_uri' => $URL]);
            $response = $client->request('GET', $URL);
            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody(), true);
            }
        } catch (\Throwable $th) {
            $responseBody = $th->getResponse();
            $body = json_decode($responseBody->getBody(), true);
            return response()->json($body, $responseBody->getStatusCode());
        }

        $this->login->createStaff($response);

        $new_response = array(
            "token" => $response["token"]["token"],
            "user_id" => $response["user_id"],
            "user_type" => $response["user_type"],
            "name_en" => $response["name_en"],
            "name" => $response["name_th"],
            "email" => $response["email"]
        );

        return response()->json($new_response, 200);
    }

    public function checkMe(Request $request)
    {
        $response = null;
        try {
            $token = $request->header('Authorization');
            $headers = ['Authorization' => $token];
            $URL = env('SSO_URL') . "/me";
            // http://gatewayservice.sit.kmutt.ac.th/api/oauth/token?client_secret=b46Ivmua&client_id=IlNvm&code=SOn2MTlB1I
            $client = new Client(['base_uri' => $URL, 'headers' => $headers]);
            $response = $client->request('GET', $URL,);
            $body = json_decode($response->getBody(), true);

            $new_body = array(
                "user_id" => $body["user_id"],
                "user_type" => $body["user_type"],
                "name" => $body["name_th"],
                "email" => $body["email"]
            );

            return response()->json($new_body, 200);
        } catch (\Throwable $th) {
            $responseBody = $th->getResponse();
            $body = json_decode($responseBody->getBody(), true);
            return response()->json($body, $responseBody->getStatusCode());
        }
    }
}
