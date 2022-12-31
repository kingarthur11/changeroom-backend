<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client as OClient;
use Illuminate\Foundation\Application;

class UserAuthRepository extends BaseRepository
{
    public function __construct(Application $app)
    {
        $this->app = $app;

    }
    protected $fieldSearchable = [
        
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return User::class;
    }

    public function registerUser($request)
    {
        $user_exist =  User::orWhere('phone_number', $request->phone_number)->orWhere('email', $request->email)->first(['email', 'phone_number']);
        if ($user_exist) {
            return ['status' => false, 'response_message' => 'Email or phone number record already exist', 'data' => 'Email or phone number record already exist'];
        }

       
        try {

            $data = $this->createUser($request);

            return ['status' => true, 'response_message' => 'User data created successfully', 'data' => $data];
        } catch (\Exception $e) {
            logger('error' . $e->getMessage() . ' =>>>>' . $e->getTraceAsString());
            return ['status' => false, 'response_message' => 'User registration process was not successful', 'data' => 'User registration process was not successful'];
        }
    }

    /**
     * @Route("Route", name="RouteName")
     */
    public function createUser($request)
    {
       
        $user = User::create([
            'name' =>  $request->name,
            'email' =>  $request->email,
            'password' =>  Hash::make($request->password),
            'phone_number' =>  $request->phone_number,
            'country_id' =>  $request->country_id,
        ]);
       
        return $user;
    }

    public function loginUser($request)
    {
        $validate = $this->validateLogin($request);
        if (isset($validate['status'])) {
            return ['status' => false, 'response_message' => $validate['message'], 'data' => $validate['data']];
        }

        $conditions = array(
            'email' => $request->input('email'),
            'password' => $request->input('password')
        );

        if (auth()->guard('user')->attempt($conditions)) {

            config(['auth.guards.api.provider' => 'users']);

            $response['success'] = 'Successfully logged in';
            $response["user"] = User::findOrFail(auth()->guard('user')->user()->id);

            $oClient = OClient::where( ['password_client'=> 1, 'provider' => 'users'])->latest()->first();

            $body = [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => request('email'),
                'password' => request('password'),
                'scope' => '*'
            ];
            
            $request = Request::create('/oauth/token', 'POST', $body);
            $result = $this->app->handle($request);
            
            $result = json_decode($result->getContent(), true);
            $response['token'] = $result['access_token'];
            $response['refresh_token'] = $result['refresh_token'];

            return ['status' => true, 'response_message' => 'User signed in successfuly, ', 'data' => $response];
        }else{
            return ['status' => false, 'response_message' => 'Unauthorised user, email or password credentials incorrect', 'data' => 'Unauthorised user, email or password credentials incorrect'];
        }

    }

    public function validateLogin($request) {
        $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|string',
            ]);

        if($validateUser->fails()){
            return ['status' => false, 'response_message' => 'validation error', 'data' => $validateUser->errors()];
        }

    }

    public function updateUser($request)
    {
        $user_auth = Auth::user();

        $success = $this->update_user($request, $user_auth->id);
        if(!$success) {
            return ['status' => false, 'response_message' => 'Update was not successful', 'data' => 'Update was not successful'];
        }
        
        return ['status' => true, 'response_message' => 'Updated successfully' ];
        
    }

    public function update_user($request, $user_id)
    {
        User::where('id', $user_id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
          ]);
    }

    public function changePassword($request) {
        $input = $request->all();
        $validation = Validator::make($input, [
            'old_password' => 'required|min:8',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        if($validation->fails()){
            return ['status' => false, 'response_message' => 'validation error', 'data' => $validation->errors()];
        }

        $user_auth = Auth::user();

        if(Hash::check($input['old_password'], $user_auth->password)){
            $input['password'] = Hash::make($input['password']);
            User::where('id', $user_auth->id)->update([
                'password' => $input['password'],
              ]);

            return ['status' => true, 'response_message' => 'Password updated successfully' ];
        }
    }
}
