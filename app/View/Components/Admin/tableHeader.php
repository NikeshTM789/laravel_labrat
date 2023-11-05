<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class tableHeader extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $module, public $title = null)
    {
         $this->title = $title;
        $this->module = $module;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.table-header');
    }
}
