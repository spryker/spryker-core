<?php

namespace Unit\SprykerEngine\Client\Kernel\Fixtures;

use SprykerEngine\Client\Kernel\Factory;

/**
 * Class KernelFactory
 * @package Unit\SprykerEngine\Yves\Kernel\Business\Fixtures
 */
class KernelFactory extends Factory
{
    /**
     * @var string
     */
    protected $classNamePattern = '\\Unit\\{{namespace}}\\Client\\{{bundle}}{{store}}\\Fixtures\\';

}
