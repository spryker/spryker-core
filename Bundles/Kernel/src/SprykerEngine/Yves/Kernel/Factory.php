<?php

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\AbstractFactory;

/**
 * Class Factory
 * @package SprykerEngine\Yves\Kernel\Business
 */
class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Yves\\{{bundle}}{{store}}\\';
}
