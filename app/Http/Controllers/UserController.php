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
        $user = new User();

        $user->userID = $request->userID;

        $user->subscriptionId = $request->subscriptionId;
        $user->save();
        $credentials = request(['userID', 'subscriptionId']);
        if (!$token = JWTAuth::attempt($credentials)) {
            $deleteUser = User::where('userID', $request->userID)->Where('subscriptionId', $request->subscriptionId)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Invalid Auth, could not create token' ,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $client = new Client($headers);
        $res = $client->get(
            'http://localhost:8000/api/server/subscribe',
            ['subscriptionId' => $request->subscriptionId, 'userID' => $request->userID, 'msisdn' => $request->msisdn, 'operatorId' => $request->operatorId, 'token' => $token]
        );
        if ($res->getStatusCode() == 200) {

            User::where('userID', $request->userID)
                ->Where('subscriptionId', $request->subscriptionId)
                ->update(
                    ['subscriptionStatus' => 'success'],
                    ['token' => $token]
                );

            return response()->json([
                'success' => true,
            ], Response::HTTP_OK);
        } else {
            $deleteUser = User::where('userID', $request->userID)->Where('subscriptionId', $request->subscriptionId)->delete();
        }
    }

    public function unsubscribe(Request $request)
    {
        $this->validate($request, [
            'userID' => 'required'
        ]);
        $credentials = request(['userID', 'subscriptionId']);


        if (!$token = JWTAuth::attempt($credentials)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid Auth, could not create token',
            ], Response::HTTP_UNAUTHORIZED);
        }


        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $client = new Client($headers);
        $res = $client->get(
            'http://localhost:8000/api/server/unsubscribe',
            ['token' => $token, 'userID' => $request->userID, 'subscriptionId' => $request->subscriptionId]
        );
        if ($res->getStatusCode()) {

            return response()->json([
                'success' => true,
            ], Response::HTTP_OK);
        }
    }
}
