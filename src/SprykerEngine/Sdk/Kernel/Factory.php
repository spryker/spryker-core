<?php

namespace SprykerEngine\Sdk\Kernel;

use SprykerEngine\Shared\Kernel\AbstractFactory;

/**
 * Class Factory
 * @package SprykerEngine\Sdk\Kernel\Business
 */
class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Sdk\\{{bundle}}{{store}}\\';
}
