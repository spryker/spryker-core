<?php

namespace Unit\SprykerEngine\Sdk\Kernel\Fixtures;

use SprykerEngine\Sdk\Kernel\Factory;

/**
 * Class KernelFactory
 * @package Unit\SprykerEngine\Yves\Kernel\Business\Fixtures
 */
class KernelFactory extends Factory
{
    /**
     * @var string
     */
    protected $classNamePattern = '\\Unit\\{{namespace}}\\Sdk\\{{bundle}}{{store}}\\Fixtures\\';

}
