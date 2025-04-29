<?php

namespace App\View\Components;

use App\Helpers\AlgerianProvinces;
use Illuminate\View\Component;

class ProvinceSelect extends Component
{
    /**
     * The name of the field.
     *
     * @var string
     */
    public $name;

    /**
     * The id of the field.
     *
     * @var string
     */
    public $id;

    /**
     * The currently selected value.
     *
     * @var string|null
     */
    public $selected;

    /**
     * Whether the field is required.
     *
     * @var bool
     */
    public $required;

    /**
     * Additional class attributes.
     *
     * @var string
     */
    public $class;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string|null $id
     * @param string|null $selected
     * @param bool $required
     * @param string $class
     * @return void
     */
    public function __construct($name, $id = null, $selected = null, $required = false, $class = '')
    {
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->selected = $selected;
        $this->required = $required;
        $this->class = $class;
    }

    /**
     * Get all provinces.
     *
     * @return array
     */
    public function provinces()
    {
        return AlgerianProvinces::getProvinces();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.province-select');
    }
} 