<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;


class UserController extends Controller
{
    public function subscribe(Request $request)
    {

        $this->validate($request, [
            'subscriptionId' => 'required',
            'userID' => 'required',
            'msisdn' => 'required',
            'operatorId' => 'required'
        ]);

        if (!$jwt_token = JWTAuth::attempt($request->userID)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Auth',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->respondWithToken($jwt_token);
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $client = new Client();
        $res = $client->get(
            'http://localhost/server/subscribe',
            ['subscriptionId' => $request->subscriptionId, 'userID' => $request->userID, 'msisdn' => $request->msisdn, 'operatorId' => $request->operatorId, 'token' => $token]
        );
        if ($res->getStatusCode()) {

            User::where('userID', $request->userID)
                ->update(['subscriptionStatus' => 'success']);

            return response()->json([
                'success' => true,
            ], Response::HTTP_OK);
        }
    }

    public function unsubscribe(Request $request)
    {
        $this->validate($request, [
            'userID' => 'required'
        ]);

        if (!$jwt_token = JWTAuth::attempt($request->userID)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Auth',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->respondWithToken($jwt_token);

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $client = new Client($headers);
        $res = $client->get(
            'http://localhost/server/unsubscribe',
            ['token' => $token, 'userID' => $request->userID]
        );
        if ($res->getStatusCode()) {

            return response()->json([
                'success' => true,
            ], Response::HTTP_OK);
        }
    }
}
