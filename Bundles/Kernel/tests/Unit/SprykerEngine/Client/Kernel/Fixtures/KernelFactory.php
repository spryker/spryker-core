<?php

namespace Unit\SprykerEngine\Client\Kernel\Fixtures;

use SprykerEngine\Client\Kernel\Factory;

class KernelFactory extends Factory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\Unit\\{{namespace}}\\Client\\{{bundle}}{{store}}\\Fixtures\\';

}
