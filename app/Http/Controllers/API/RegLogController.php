<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//calling Models
use App\Role;
use App\User;
use App\Location;

use Validator;
use Auth;


class RegLogController extends Controller
{
      private $apiToken;
      public function __construct()
      {
        // Unique Token
        $this->apiToken = uniqid(base64_encode(str_random(60)));
      }
      /**
       * Client Login
       */
      public function postLogin(Request $request)
      {
        // Validations
        $rules = [
          'email'=>'required|email',
          'password'=>'required|min:6'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          //Validation failed
          $message = $validator->messages();
          if ($message) {
            return response()->json($message);
          }
          // else{
          //   return response()->json("Wrong");
          // }
          
        } 
        else {
          //Fetch User
          $user = User::where('email',$request->email)->first();
          if($user) {
            // Verify the password
            if( password_verify($request->password, $user->password) ) {
              $token = $this->apiToken;
              $postArray = ['api_token' => $token, ];
              $login = User::where('email',$request->email)->update($postArray);
              
              if($login) {
                return response()->json([
                                        'message' => ' Welcome '.$user->first_name. ' '.$user->last_name.' ',

                            'Tokens' => $token,
                            
                            
                ]);
                return response()->json(Auth::user()->id);
              }
            } else {
              return response()->json([
                'message' => 'Invalid Password',
              ]);
            }
          } else {
            return response()->json([
              'message' => 'User not found',
            ]);
          }
        }
      }
      /**
       * Register
       */
      public function postRegister(Request $request)
        {
          // Validations
          $rules = [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'phone' => 'required|regex:/[07]{2}[2,3,8]{1}[0-9]{7}/',
            'email'    => 'required|unique:users,email',
            'role' => 'required',
            'password' => 'required|min:8|confirmed',
            'province' => 'required|min:3',
            'district' => 'required',
            'sector' => 'required',
          ];
          $validator = Validator::make($request->all(), $rules);
          if ($validator->fails()) {
            // Validation failed
            return response()->json([
              'message' => $validator->messages(),
            ]);
          } 
          else {
            $done = DB::table('users')

            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.role_name', '=', 'Admin')
            ->orWhere('users.role_id', '=', 1)
            ->get();
            if ($done) {
              return response()->json([
                'message' => 'You are not Allowed To be Admin'
              ]);
            }
              $role = DB::table('users')

            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.role_name', '=', $request->role)
            ->get();

            if ($request->role == 'Buyer' || $done[0]->role_name == 'Buyer') {
                $save = new Location();
                $save->province=$request->province;
                $save->district=$request->district;
                $save->sector=$request->sector;
                $save->save();

                $loc = DB::table('users')

                ->join('locations', 'users.location_id', '=', 'location.id')
                ->where('locations.province', '=', $request->province)
                ->where('locations.district', '=', $request->district)
                ->where('locations.sector', '=', $request->sector)
                ->get();

                $postArray = [
                  'first_name' => $request->first_name,
                  'last_name' => $request->last_name,
                  'phone' => $request->phone,
                  'email'     => $request->email,
                  'password'  => bcrypt($request->password),
                  'role_id'  => $role[0]->id,
                  'location_id' => $loc[0]->id,
                  'api_token' => $this->apiToken
                ];
                
                
                $user = User::insert($postArray);

                $order = new Order();
                $order->user_id=$user->id;
                $order->product_id=0;
                $order->location=$save->id;
                $order->save();
            
                if($user) {
                  return response()->json([
                    'name'         => $request->first_name,
                    'email'        => $request->email,
                    'access_token' => $this->apiToken,
                  ]);
                } else {
                  return response()->json([
                    'message' => 'Registration failed, please try again.',
                  ]);
                }
            }
            $save = new Location();
            $save->province=$request->province;
            $save->district=$request->district;
            $save->sector=$request->sector;
            $save->save();

            $loc = DB::table('users')

            ->join('locations', 'users.location_id', '=', 'location.id')
            ->where('locations.province', '=', $request->province)
            ->where('locations.district', '=', $request->district)
            ->where('locations.sector', '=', $request->sector)
            ->get();

            $postArray = [
              'first_name' => $request->first_name,
              'last_name' => $request->last_name,
              'phone' => $request->phone,
              'email'     => $request->email,
              'password'  => bcrypt($request->password),
              'role_id'  => $role[0]->id,
              'location_id' => $loc[0]->id,
              'api_token' => $this->apiToken
            ];
            
            
            $user = User::insert($postArray);
        
            if($user) {
              return response()->json([
                'name'         => $request->first_name,
                'email'        => $request->email,
                'access_token' => $this->apiToken,
              ]);
            } else {
              return response()->json([
                'message' => 'Registration failed, please try again.',
              ]);
            }
          }
        }

      /**
       * Logout
       */
       public function postLogout(Request $request)
      {
        $token = $request->header('Authorization');
        $user = User::where('api_token',$token)->first();
        if($user) {
          $postArray = ['api_token' => null];
          $logout = User::where('id',$user->id)->update($postArray);
          if($logout) {
            return response()->json([
              'message' => ''.$user->first_name.' '.$user->last_name.' Logged Out',
            ]);
          }
        } else {
            $postArray = ['api_token' => $this->apiToken];
          // $postArray = ['api_token' => $this->token];
          return response()->json([
            'message' => 'User not found',
            'access_token' => $this->apiToken,
            // 'access_token' => $this->token,
          ]);
        }
      }
}
