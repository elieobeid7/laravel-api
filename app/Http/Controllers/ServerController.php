<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class ServerController extends Controller
{
    public function subscribeCallback(Request $request)
    {
        $jwt_token = null;
        $input = $request->only('token');


        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Auth',
            ], Response::HTTP_UNAUTHORIZED);
        } else {
            $user = new User();

            $user->userID = $request->userID;

            $user->subscriptionId = $request->subscriptionId;
            $user->msisdn = $request->msisdn;
            $user->operatorId = $request->operatorId;
            $user->save();

            return response()->json([
                'success' => true,
                'data' => $user
            ], Response::HTTP_OK);
        }
    }

    public function unsubscribeCallback(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'userID' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            $deleteUser = User::where('userID', $request->userID )->delete();

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
