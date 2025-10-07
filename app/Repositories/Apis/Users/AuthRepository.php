<?php
declare(strict_types=1); 
namespace App\Repositories\Apis\Users;

use App\Interfaces\UserInterface;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthRepository implements UserInterface {


    /**
     * This function is used to register a new user into the system
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\JsonResponse 
     */
    public function registerHandler(Request $request)
    {
        try {
            // validation
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:5|max:16'
            ]);

            if ($validator->fails()) {
                return response()->apiError("You have some validation errors", 422, $validator->errors());
            }

            User::create($request->all());
            return response()->apiSuccess($request->all(), "User registered successfully", 201);
        

        } catch(Exception $e) {
            return response()->apiCatchError();
        }
    }


    /**
     * Validating login credentials
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\JsonResponse 
     */

    public function loginHandler(Request $request)
    {
        try {
            // validation
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->apiError("You have some validation errors", 422, $validator->errors());
            }

            $credentials = $request->all();

            if (! $token = auth('api')->attempt($credentials)) {
                return response()->apiError('Unauthorized', 401, 'Given email or password are not matched in our system');
            }
            return $this->respondWithToken($token);

        } catch(Exception $e) {
            return response()->apiCatchError();
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * User Logout
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->apiSuccess([], 'Successfully logged out', 200);
    }



    protected function respondWithToken($token)
    {
        $user = auth('api')->user();
        $responseArray = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user_details' => [
                'name'  => $user->name,
                'email' => $user->email
            ]
        ];
        return response()->apiSuccess($responseArray, 'User loggedIn successfully', 200);
    }




    public static function isDuplicate(string $email, int $id = 0): bool
    {
        try {
            return User::where('email', $email)
                ->when($id > 0, function($query) use ($id) {
                    $query->where('id', '!=', $id);
                })->count() >= 1 ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

}
