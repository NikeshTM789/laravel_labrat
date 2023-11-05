<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal extends Component
{
    public $size = null;
    public $modalid = null;
    public $model_title = null;
    /**
     * Create a new component instance.
     */
    public function __construct($modalid = "modal", $size = "sm", $model_title = "")
    {
        $this->modalid = $modalid;
        $this->size = $size;
        $this->model_title = $model_title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal');
    }
}
