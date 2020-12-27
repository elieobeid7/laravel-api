<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends Controller
{
    public function subscribe(Request $request)
    {
        $jwt_token = null;
        $input = $request->only('userID', 'subscriptionId');


        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Auth, could not verify token',
            ], Response::HTTP_UNAUTHORIZED);
        } else {
            User::where('userID', $request->userID)
            ->Where('subscriptionId', $request->subscriptionId)
            ->update(['msisdn' => $request->msisdn], ['operatorId' => $request->operatorId] );


            return response()->json([
                'success' => true
            ], Response::HTTP_OK);
        }
    }

    public function unsubscribe(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'userID' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            $deleteUser = User::where('userID', $request->userID )->Where('subscriptionId', $request->subscriptionId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'User unsubscribed successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be unsubscribed '
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
