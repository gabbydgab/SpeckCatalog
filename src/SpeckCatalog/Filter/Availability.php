<?php

namespace SpeckCatalog\Filter;

use Zend\InputFilter\InputFilter;

class Availability extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'product_id',
        ));
        $this->add(array(
            'name' => 'uom_code',
        ));
        $this->add(array(
            'name' => 'quantity',
        ));
        $this->add(array(
            'name' => 'distributor_id',
        ));
        $this->add(array(
            'name' => 'cost',
            'allow_empty' => false,
        ));
        $this->add(array(
            'name' => 'distributor_item_number',
        ));
        $this->add(array(
            'name' => 'distributor_uom_code',
        ));
    }
}
