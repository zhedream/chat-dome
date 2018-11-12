<?php

namespace app\Requests;

class LoginRequest extends FromRequest
{


    public function authorize(){
        return true;
    }
    
    public function rules(){

        return [
            'username'=>[
                'required',
                function($val){
                    var_dump($val);
                    if($val=='' || !$val){
                        return false;
                    }
                    return true;
                }
            ],
            'password'=>[
                'password',
                function($val){

                    return false;
                }
            ],
            
        ];
    }


}