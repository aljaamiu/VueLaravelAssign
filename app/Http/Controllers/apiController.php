<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


////for api
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

///models
use App\Models\Subscriber;
use App\Models\SubscriberProperty;
use App\Models\SubscriberPropertyValue;

class apiController extends Controller
{
    //
    public function apilogin(Request $request){

        // $password = $request->password;
        // $email = $request->email;

        // $request->password = 12345678;
        // $request->email = 'emjay@gmail.com';

        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
              return response()->json([
                'status_code' => 500,
                'message' => 'Unauthorized'
              ]);
            }
            $user = User::where('email', $request->email)->first();
            if ( ! Hash::check($request->password, $user->password, [])) {
               throw new \Exception('Error in Login');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
              'status_code' => 200,
              'access_token' => $tokenResult,
              'token_type' => 'Bearer',
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
        
    }

    ///
    public function getall(){

        // return "it's emjay";
        $subscriber = DB::table('subscribers')
        ->join('subscriber_property_values', 'subscribers.id', 'subscriber_property_values.subscriber_id')
        ->join('subscriber_properties', 'subscriber_property_values.subscriber_property_id', 'subscriber_properties.id')
        // ->get();
        ->paginate(3);

        if(count($subscriber)=== 0){

            //
            $subscribers = 'Nothing Found';
            return $subscribers;
            
        }else{
            $subscribers = $subscriber;
            return $subscribers;
        }
        
    }

    ////get one not in use
    public function getone($s_id = null){

        if( !empty($s_id) ){
            
            $s_id = $s_id;

            $asubscriber1 = DB::table('subscribers')
            ->where('subscribers.id', $s_id)
            ->join('subscriber_property_values', 'subscribers.id', 'subscriber_property_values.subscriber_id')
            ->join('subscriber_properties', 'subscriber_property_values.subscriber_property_id', 'subscriber_properties.id')
            ->get();

            if(count($asubscriber1)=== 0){

                //
                $asubscriber = 'none';
                return $asubscriber;
                
            }else{
                $asubscriber = $asubscriber1;
                return $asubscriber;
            }

        }else{
            //fill all
            return 'No ID Given';
        }
        
    }

    ////save
    public function save(Request $req){

        if( !empty($req->name) && !empty($req->phone) && !empty($req->org_id) && !empty($req->is_private) ){
            //
            $name = ucwords( $req->name);
            $org_id = $req->org_id;
            $private = $req->is_private;
            $phone = $req->phone;
            $active = 1;

            if (ctype_digit($phone)) {
                // MAKE CONTACTS INTERNATIONAL FORMAT FOR TEXT MESSAGING PURPOSES SOMEDAY
                if (preg_match("~^0\d+$~", $phone)) {
                    $phone = preg_replace('/0/', '233', $phone, 1);
                }else {
                    $phone = $phone;
                }
                //
                if(preg_match("/^([-a-z ])+$/i",$name)){
                    //

                    $val = new Subscriber();
                    $val->phone= $phone;
                    $val->org_id= $org_id;
                    $val->is_active= $active;
                    
                    $val->save();

                    ////////
                    $last_ids1 = DB::table('subscribers')
                    ->where('phone', $phone)
                    ->where('org_id', $org_id)
                    ->where('is_active', $active)
                    ->get();

                    foreach ($last_ids1 as $key => $lasts) {
                        # code...
                        $s_id = $lasts->id;
                    }
                    ////////////     ///////////////////

                    $val2 = new SubscriberProperty();
                    $val2->name= $name;
                    $val2->org_id= $org_id;
                    $val2->is_private= $private;
                    
                    $val2->save();

                    ////////
                    $last_ids2 = DB::table('subscriber_properties')
                    ->where('name', $name)
                    ->where('org_id', $org_id)
                    ->where('is_private', $private)
                    ->get();

                    foreach ($last_ids2 as $key => $lasts2) {
                        # code...
                        $sp_id = $lasts2->id;
                    }

                    ///////////////       ///////////////////////
                    $val3 = new SubscriberPropertyValue();
                    $val3->value= $name;
                    $val3->org_id= $org_id;
                    $val3->subscriber_id= $s_id;
                    $val3->subscriber_property_id= $sp_id;
                    
                    $val3->save();

                    // return 'Done';
                    return response()->json(['success'=>'Done', 'req'=> $req]);
                    // return $req;
                }else{
                    //invalid name
                    return response()->json(['success'=>'Invalid Name', 'req'=> $req]);
                    // return 'Invalid Name';
                }
            }else{
                //invalid phone no.
                return response()->json(['success'=>'Invalid Phone No.', 'req'=> $req]);
                return 'Invalid Phone No.';
            }

        }else{
            //fill all
            return response()->json(['success'=>'Fill All', 'req'=> $req]);
            return 'Fill All';
        }
        
    }

    ///delete
    public function remove($spv_id = null){

        if( !empty($spv_id) ){
            
            $spv_id = $spv_id;

            $check1 = DB::table('subscriber_property_values')
            ->where('v_id', $spv_id)
            ->get();

            if( count($check1) === 0 ){
                //
                return response()->json(['success'=>'Not Found', 'req'=> $spv_id]);
                return 'Not Found';
            }else{
                //
                foreach ($check1 as $key => $check) {
                    # code...
                    $s_id = $check->subscriber_id;
                    $sp_id = $check->subscriber_property_id;
                }

                $remove3 = DB::table('subscriber_property_values')
                ->where('subscriber_property_values.v_id', $spv_id)
                ->delete();
                
                //////////      /////////////
                
                $remove2 = DB::table('subscriber_properties')
                ->where('subscriber_properties.id', $sp_id)
                ->delete();

                //////////      /////////////
                $remove = DB::table('subscribers')
                ->where('subscribers.id', $s_id)
                ->delete();

                // return 'Done';
                return response()->json(['success'=>'Done', 'req'=> $spv_id]);
                // return $check1;
            }

        }else{
            //fill all
            return 'No ID Given';
        }
        
    }

    ///update
    public function update(Request $req){

        //return $req; //{"name":"Muhammed Jamiu","is_active":1,"is_private":1,"v_id":3,"phone":"233241234569","org_id":"1","subscriber_property_id":null,"subscriber_id":3,"styleObject":{"width":"100px","height":"100px"},"id":3}

        if( !empty($req->v_id) && !empty($req->name) && !empty($req->phone) && !empty($req->org_id) && !empty($req->is_private) && !empty($req->is_active) ){
            //
            $name = ucwords( $req->name);
            $org_id = $req->org_id;
            $private = $req->is_private;
            $phone = $req->phone;
            $active = $req->is_active;
            $spv_id = $req->v_id;

            if (ctype_digit($phone)) {
                // MAKE CONTACTS INTERNATIONAL FORMAT FOR TEXT MESSAGING PURPOSES SOMEDAY
                if (preg_match("~^0\d+$~", $phone)) {
                    $phone = preg_replace('/0/', '233', $phone, 1);
                }else {
                    $phone = $phone;
                }
                //
                if(preg_match("/^([-a-z ])+$/i",$name)){

                    $check1 = DB::table('subscriber_property_values')
                    ->where('v_id', $spv_id)
                    ->get();

                    if( count($check1) === 0 ){
                        //
                        return 'Not Found';
                    }else{
                        //
                        foreach ($check1 as $key => $check) {
                            # code...
                            $s_id = $check->subscriber_id;
                            $sp_id = $check->subscriber_property_id;
                        }

                        $update = DB::table('subscribers')
                        ->where('subscribers.id', $s_id)
                        ->update([
                            'phone' => $phone,
                            'org_id' => $org_id,
                            'is_active' => $active
                            ]);
                        
                        //////////      /////////////
                        
                        $update2 = DB::table('subscriber_properties')
                        ->where('subscriber_properties.id', $sp_id)
                        ->update([
                            'name' => $name,
                            'org_id' => $org_id,
                            'is_private' => $private
                            ]);

                        //////////      /////////////
                            
                        $update3 = DB::table('subscriber_property_values')
                        ->where('subscriber_property_values.v_id', $spv_id)
                        ->update([
                            'value' => $name,
                            'org_id' => $org_id
                            ]);

                        // return 'Done';
                        return response()->json(['success'=>'Done', 'req'=> $req]);
                        return $req;
                    }
                    
                }else{
                    //invalid name
                    return response()->json(['success'=>'Invalid Name', 'req'=> $req]);
                    return 'Invalid Name';
                }
            }else{
                //invalid phone no.
                return response()->json(['success'=>'Invalid Phone No.', 'req'=> $req]);
                return 'Invalid Phone No.';
            }

        }else{
            //fill all
            return response()->json(['success'=>'Fill All', 'req'=> $req]);
            return 'Fill All';
        }
        
    }
}
