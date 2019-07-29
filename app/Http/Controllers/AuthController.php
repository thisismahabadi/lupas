<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\User;
use Validator;
use App\Response;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

 /**
  * @version 1.0.0
  */
class AuthController extends Controller
{
    /**
     * The client access result.
     *
     * @since 1.0.0
     *
     * @var string
     */
    private $client;

    /**
     * Construct method which call when an instance has been created.
     *
     * @since 1.0.0
     *
     */
    public function __construct()
    {
        $this->client = Client::where('password_client', true)->orderBy('id', 'desc')->first();
    }

    /**
     * Register new user.
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string|required',
                'email' => 'email|required|unique:users',
                'password' => 'string|required',
            ]);

            if ($validator->fails()) {
                return Response::generate('error', $validator->errors()->getMessages(), 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return Response::generate('success', 'User has been registered successfully.', 201);
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Login user.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for login
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'email|required',
                'password' => 'string|required',
            ]);

            if ($validator->fails()) {
                return Response::generate('error', $validator->errors()->getMessages(), 422);
            }

            $user = User::where('email', $request->email)->first();

            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $params = [
                        'grant_type' => 'password',
                        'client_id' => $this->client->id,
                        'client_secret' => $this->client->secret,
                        'username' => $request->email,
                        'password' => $request->password,
                        'scope' => '*'
                    ];
                    $proxy = Request::create('oauth/token', 'POST', $params);
                    $oauth = app()->handle($proxy);
                    $data =  json_decode($oauth->getContent(), true);

                    return Response::generate('success', $data, 200);
                } else {
                    return Response::generate('error', 'Unauthenticated', 401);
                }
            }

            return Response::generate('error', 'Unauthenticated', 401);            
        } catch (Exception $e) {
            return parent::response('error', $e->getMessage(), 500);
        }
    }

    /**
     * Exchange a refresh token for an access token when the access token has expired.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for requesting refresh token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'refresh_token' => 'required',
            ]);

            if ($validator->fails()) {
                return Response::generate('error', $validator->errors()->getMessages(), 422);
            }

            $params = [
                'grant_type' => 'refresh_token',
                'refresh_token' => $request->refresh_token,
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
                'scope' => ''
            ];
            $proxy = Request::create('oauth/token', 'POST', $params);
            $oauth = app()->handle($proxy);
            $data =  json_decode($oauth->getContent(), true);

            return Response::generate('success', $data, 200);
        } catch (Exception $e) {
            return Response::generate('error', $e->getMessage(), 500);
        }
    }

    /**
     * Logout from current user.
     *
     * @since 1.0.0
     *
     * @todo Improve better way for logout
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $accessToken = Auth::user()->token();
            DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->update(['revoked' => true]);
            $accessToken->revoke();

            return Response::generate('error', 'User has been logout successfully.', 204);
        } catch (Exception $e) {
            return Response::generate('error', $e->getMessage(), 500);
        }
    }
}
