<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * Returns authenticated user profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request)
    {
        $user = $request->user();

        $profile = User::with([
            'restaurant' => function(HasOne $query) {
                $query->select([
                    'id', 'user_id', 'name', 'address', 'image', 'lat', 'lng', 'restaurant_balance',
                ]);
            }
        ])->findOrFail($user->id);

        $profile->restaurant->makeHidden('user_id');

        return response()->json($profile, 200);
    }

    /**
     * Update user profile.
     *
     * @param UpdateProfile $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateProfile $request)
    {
        try {
            $user = $request->user();

            $data = $request->only(['name', 'email']);
            if (!empty($request->input('password'))) {
                $data['password'] = bcrypt($data['password']);
            }
            $user->update($data);

            return response()->json($user);
        } catch (QueryException $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Update user profile.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function updatePhoto(Request $request)
    {
        $this->validate($request, [
            'avatar' => 'required|file|mimes:jpeg,jpg,png,gif|image|max:2000'
        ]);

        try {
            $user = $request->user();

            $file = $request->file('avatar');
            $path = $file->storePubliclyAs(
                'avatars/' . date('Ym'),
                $user->id . '.' . $file->extension(),
                'public'
            );

            if ($path === false) {
                return response()->json([
                    'errors' => 'Store file into storage failed'
                ], 500);
            }

            $user->update(['avatar' => $path]);

            return response()->json($user);
        } catch (QueryException $e) {
            return response()->json([
                'errors' => 'Something went wrong, try again or contact administrator'
            ], 500);
        }
    }

    /**
     * Get user order.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function orders(Request $request)
    {
        $orders = $request->user()->orders()->latest()->paginate(10);

        return response()->json($orders);
    }

    /**
     * Get user transactions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function transactions(Request $request)
    {
        $transactions = $request->user()->transactions()->latest();

        if ($request->filled('type')) {
            $transactions->type($request->get('type'));
        }

        if ($request->filled('status')) {
            $transactions->status($request->get('status'));
        }

        return response()->json($transactions->paginate(10));
    }
}
