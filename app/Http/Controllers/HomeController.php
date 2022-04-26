<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appuser;
use App\Models\Personinfo;

class HomeController extends Controller
{
     
    public function login() {
        $response =  array("status" => "success");
        echo json_encode($response);
    }

    public function authenticate(Request $request) {
         //  print_r($request);
         
         if((!isset($request->apiKey)) || ($request->apiKey != config("constants.MY_APP_KEY"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } elseif((!isset($request->appID)) || ($request->appID != config("constants.MY_APP_ID"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } else {   
           $result = Appuser::verifyUser($request->loginID, $request->uPassword);

           if(isset($result[0]->email)) {

            $payload = array('sub'=> config("constants.MY_APP_ID"),'email'=> $result[0]->email, 'admin'=>true, 'exp'=>(time() + 3600));
            $jwt_token = $this->generate_jwt($payload); 
            $response =  array("status" => "success", 'email' => $result[0]->email, 'token' => $jwt_token );
            
           } else {
            $response =  array("status" => "failure", 'msg' =>  'Invalid user ID/Password');
           }
        }  
           
            return response()->json($response, 200);
         

    }



    public function addperson(Request $request) {

        if((!isset($request->apiKey)) || ($request->apiKey != config("constants.MY_APP_KEY"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } elseif((!isset($request->appID)) || ($request->appID != config("constants.MY_APP_ID"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } else {   
           
            if($this->is_jwt_valid($request->jwt) == true) {
                $personinfo = new Personinfo;
                $personinfo->individual_name = $request->individual_name;
                $personinfo->designation = $request->designation;
                $personinfo->email = $request->email;
                $personinfo->gender = $request->gender;
                $personinfo->person_status = $request->person_status;
                $personinfo->address_line1 = $request->address_line1;
                $personinfo->address_line2 = $request->address_line2;
                $personinfo->city = $request->city;
                $personinfo->country_code = $request->country_code;
                $personinfo->save();
                $response =  array("status" => "success", "token" => $request->jwt);
            }  else {
                $response =  array("status" => "failure", "msg" => "Token Expired !");
            } 

        }  
        return response()->json($response, 200);
       // return response()->json($request, 200);
    }


    public function updateperson(Request $request) {

        if((!isset($request->apiKey)) || ($request->apiKey != config("constants.MY_APP_KEY"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } elseif((!isset($request->appID)) || ($request->appID != config("constants.MY_APP_ID"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } else {   
           
            if($this->is_jwt_valid($request->jwt) == true) {
                $personinfo = Personinfo::find($request->person_id);
                $personinfo->individual_name = $request->individual_name;
                $personinfo->designation = $request->designation;
                $personinfo->email = $request->email;
                $personinfo->gender = $request->gender;
                $personinfo->person_status = $request->person_status;
                $personinfo->address_line1 = $request->address_line1;
                $personinfo->address_line2 = $request->address_line2;
                $personinfo->city = $request->city;
                $personinfo->country_code = $request->country_code;
                $personinfo->save();
                $response =  array("status" => "success", "token" => $request->jwt);
            }  else {
                $response =  array("status" => "failure", "msg" => "Token Expired !");
            } 

        }  
        return response()->json($response, 200);
       // return response()->json($request, 200);
    }


    public function deleteIndividualData(Request $request) {

        if((!isset($request->apiKey)) || ($request->apiKey != config("constants.MY_APP_KEY"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } elseif((!isset($request->appID)) || ($request->appID != config("constants.MY_APP_ID"))) {
            $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
         } else {   
           
            if($this->is_jwt_valid($request->jwt) == true) {
                $personinfo = Personinfo::find($request->id);
                $personinfo->delete();
                $response =  array("status" => "success", "token" => $request->jwt);
            }  else {
                $response =  array("status" => "failure", "msg" => "Token Expired !");
            } 

        }  
        return response()->json($response, 200);
       // return response()->json($request, 200);
    }


    public function generate_jwt($payload) { 

        $headers = array('alg'=>'HS256','typ'=>'JWT');
        $secret = config("constants.MY_JWT_SECRET");
        $headers_encoded = $this->base64url_encode(json_encode($headers));
	    $payload_encoded = $this->base64url_encode(json_encode($payload));
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
        $signature_encoded = $this->base64url_encode($signature);
        $jwt = "$headers_encoded.$payload_encoded.$signature_encoded";
        
        return $jwt;

    }

    public function listall() {
       $all_records =  Personinfo::all();
       $response =  array("token_status" => "success", 'records' => $all_records );
       return response()->json($response, 200);
    }

    public function getIndividualData(Request $request) {
        //  print_r($request);
        
        if((!isset($request->apiKey)) || ($request->apiKey != config("constants.MY_APP_KEY"))) {
           $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
        } elseif((!isset($request->appID)) || ($request->appID != config("constants.MY_APP_ID"))) {
           $response =  array("status" => "failure", 'msg' =>  'Invalid authentication request !');  
        } elseif(!isset($request->jwt)) {  
            $response =  array("status" => "failure", 'msg' =>  'Invalid token !');  
        } else {   
            
            if($this->is_jwt_valid($request->jwt) == true) {
              $result = Personinfo::getPersonData($request->id);
              $response =  array("status" => "success", 'record' => $result, 'token' => $request->jwt );
            }  else {
              $response =  array("status" => "failure", "msg" => "Token Expired !");
            }
        
        }  
          
        return response()->json($response, 200);
        

   }


    public function base64url_encode($str) {
         return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    } 

    public function is_jwt_valid($jwt) {
        $secret = config("constants.MY_JWT_SECRET");
        // split the jwt
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];
    
        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;
    
        // build a signature based on the header and payload using the secret
        $base64_url_header = $this->base64url_encode($header);
        $base64_url_payload = $this->base64url_encode($payload);
        $signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
        $base64_url_signature = $this->base64url_encode($signature);
    
        // verify it matches the signature provided in the jwt
        $is_signature_valid = ($base64_url_signature === $signature_provided);
        
        if ($is_token_expired || !$is_signature_valid) {
            return false;
        } else {
            return true;
        }
    }

    public function verifyJwt(Request $request){

        $is_valid_jwt = $this->is_jwt_valid($request->jwt);

        if($is_valid_jwt == false ) {
            $response =  array("token_status" => "fail" );
          
        } else {
            $response =  array("token_status" => "success", 'token' => $request->jwt );
        }

        return response()->json($response, 200);

    }




}
