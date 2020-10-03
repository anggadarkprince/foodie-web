<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //$token = $user->createToken('Login')->accessToken;

        //return response()->json(['token' => $token], 200);

        return $this->login($request);
    }

    /**
     * Acquire personal access grant (token that manually revoked by user)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loginPersonalAccessToken(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            $token = auth()->user()->createToken('Login')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /**
     * Login request access and refresh token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $http = new Client();

        try {
            $response = $http->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('OAUTH_PWD_GRANT_CLIENT_ID') ,
                    'client_secret' => env('OAUTH_PWD_GRANT_CLIENT_SECRET'),
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*'
                ],
            ]);

            $tokens = json_decode((string)$response->getBody() , true);
        }
        catch(ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return response()->json('Invalid email/password combination', 401);
            }

            return response()->json('Invalid credential', 401);
        }

        return response()->json($tokens);
    }

    /**
     * Login request access and refresh token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function token(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        $http = new Client();

        try {
            $response = $http->post(env('APP_URL') . '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                    'client_id' => env('OAUTH_PWD_GRANT_CLIENT_ID') ,
                    'client_secret' => env('OAUTH_PWD_GRANT_CLIENT_SECRET'),
                    'scope' => '*'
                ],
            ]);

            $tokens = json_decode((string)$response->getBody() , true);
        }
        catch(ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Refresh token invalid or expired'
                ], 401);
            }

            throw $e;
        }

        return response()->json($tokens);
    }

    /**
     * Logout request to delete token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $accessToken = auth()->user()->token();

        // revoke current access token
        // $result = $accessToken->revoke();

        $this->revokeAccessAndRefreshTokens($accessToken->id);

        return response()->json(["result" => true]);
    }

    /**
     * Revoke access and refresh token.
     *
     * @param String $tokenId
     */
    protected function revokeAccessAndRefreshTokens(string $tokenId) {
        $tokenRepository = app('Laravel\Passport\TokenRepository');
        $refreshTokenRepository = app('Laravel\Passport\RefreshTokenRepository');

        $tokenRepository->revokeAccessToken($tokenId);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($tokenId);
    }

    /**
     * Returns authenticated user.
     *
     * @return JsonResponse
     */
    public function user()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
