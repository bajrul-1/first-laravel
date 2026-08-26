<?php

namespace App\View\Components\Owner;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCreateForm extends Component
{
    public $company_slug;

    /**
     * Create a new component instance.
     *
     * @param string|null $companySlug
     */
    public function __construct($companySlug = null)
    {
        $this->company_slug = $companySlug;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.owner.product-create-form');
    }
}