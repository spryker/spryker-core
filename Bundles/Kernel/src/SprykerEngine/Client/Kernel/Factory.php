<?php

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\AbstractFactory;

/**
 * Class Factory
 * @package SprykerEngine\Client\Kernel\Business
 */
class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Client\\{{bundle}}{{store}}\\';
}
