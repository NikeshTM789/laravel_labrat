<?php

namespace App\Traits;
/**
 * Toastr Js
 */
Trait Toastr
{
    public function __construct()
    {
        config(['adminlte.plugins.Toastr.active' => true]);
    }

    public function flash_success($message = 'Done 😎'){
    	request()->session()->flash('success', $message);
    }

    public function flash_error($message = 'Opps! something went wrong 🤬'){
    	request()->session()->flash('error', $message);
    }
}
 ?>