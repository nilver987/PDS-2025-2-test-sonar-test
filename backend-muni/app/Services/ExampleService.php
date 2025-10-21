<?php

namespace App\Services;

class ExampleService
{
    private $dependency;

    public function __construct($dependency)
    {
        $this->dependency = $dependency;
    }

    public function doSomething($param)
    {
        return $this->dependency->someMethod($param);
    }
}
