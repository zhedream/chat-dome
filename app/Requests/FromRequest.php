<?php

namespace app\Requests;

use core\Request;

class FromRequest extends Request
{
    
    public function authorize(){
        return false;
    }
    
    public function rules(){
    }

    public function message(){
    }


}