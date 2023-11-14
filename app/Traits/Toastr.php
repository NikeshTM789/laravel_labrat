<?php

namespace App\Traits;
/**
 * Toastr Js
 */
Trait Toastr
{
    public function flash_success($message = 'Done 😎'){
        $this->initToastr();
    	request()->session()->flash('ok', $message);
    }

    public function flash_error($message = 'Opps! something went wrong 🤬'){
        $this->initToastr();
    	request()->session()->flash('err', $message);
    }

    function initToastr()
    {
        // config(['adminlte.plugins.Toastr.active' => true]);
    }
}
 ?>