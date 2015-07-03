<?php

namespace Unit\SprykerEngine\Client\Kernel\Service\Fixtures;

use SprykerEngine\Client\Kernel\Service\Factory;

class KernelFactory extends Factory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\Unit\\{{namespace}}\\Client\\{{bundle}}{{store}}\\Service\\Fixtures\\';

}
