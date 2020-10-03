<?php

namespace App\Http\Controllers\Api\Courier;

use App\Models\Courier;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SanctumController extends Controller
{

    /**
     * Handles courier registration request
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:couriers',
            'password' => 'required|min:6|confirmed',
            'id_card' => 'required|max:20',
            'date_of_birth' => 'required|date',
            'address' => 'required|max:100',
            'vehicle_type' => 'required|max:20',
            'vehicle_plat' => 'required|string|min:6|max:10',
            'device_name' => 'required',
            'accept' => 'required|accepted',
        ]);

        Courier::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_card' => $request->id_card,
            'date_of_birth' => Carbon::parse($request->date_of_birth)->format('Y-m-d'),
            'address' => $request->address,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_plat' => $request->vehicle_plat,
        ]);

        return $this->login($request);
    }


    /**
     * Login request access and refresh token.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $courier = Courier::where('email', $request->email)->first();

        // Check user credentials
        if (!$courier || ! Hash::check($request->password, $courier->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Generate long-lived access token
        $token = $courier->createToken($request->device_name, ['*'])->plainTextToken;

        return response()->json([
            'device_name' => $request->device_name,
            'token' => $token
        ]);
    }

    /**
     * Logout request to delete token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        // Get user who requested the logout
        $user = $request->user();

        // Revoke current user token
        $result = $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json(["result" => $result]);
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
