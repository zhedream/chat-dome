<?php

namespace app\Requests;

class UserRequest extends FromRequest
{


    public function authorize(){
        return true;
    }
    
    public function rules(){

        return [
            'email'=>[
                'required',
                function($val){

                    return true;
                }
            ],
            'password'=>[
                'required',
                function($val){

                    return false;
                }
            ],
            
        ];
    }


}