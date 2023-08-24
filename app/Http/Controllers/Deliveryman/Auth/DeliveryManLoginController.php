<?php

namespace App\Http\Controllers\Deliveryman\Auth;

use Auth;
use Hash;
use Carbon\Carbon;
use App\Models\Vendor;
use App\Rules\Captcha;
use App\Models\DeliveryMan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryManLoginController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest:api')->except('userLogout');
    // }

   public function loginPage(){
    return view('deliveryman.login');
   }

   public function dashboardLogin(Request $request){
    $rules = [
        'email'=>'required',
        'password'=>'required',
        'g-recaptcha-response'=>new Captcha()
    ];
    $customMessages = [
        'email.required' => trans('user_validation.Email is required'),
        'password.required' => trans('user_validation.Password is required'),
    ];
    $this->validate($request, $rules,$customMessages);

    if($deliveryMan){

        if($deliveryMan->status==1){
            if(Hash::check($request->password,$deliveryMan->password)){

                $credential=[
                    'email'=> $request->email,
                    'password'=> $request->password
                ];

                if (! $token = Auth::guard('deliveryman-api')->attempt($credential, ['exp' => Carbon::now()->addDays(365)->timestamp])) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                $deliveryMan = DeliveryMan::where('email',$request->email)->select('id','fname','email','phone','man_image','status')->first();

                return $this->respondWithToken($token,0,$deliveryMan);

            }else{
                $notification = trans('user_validation.Credentials does not exist');
                return response()->json(['notification' => $notification],402);
            }

        }else{
            $notification = trans('user_validation.Disabled Account');
            return response()->json(['notification' => $notification],402);
        }
    }else{
        $notification = trans('user_validation.Email does not exist');
        return response()->json(['notification' => $notification],402);
    }
   }

   public function logout(){
        Auth::guard('deliveryman-api')->logout();
        $notification= trans('admin_validation.Logout Successfully');
        return response()->json(['notification' => $notification]);
    }

    protected function respondWithToken($token, $deliveryMan)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $deliveryMan
        ]);
    }
}
