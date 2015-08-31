<?php

namespace SprykerEngine\Client\Kernel;

use SprykerEngine\Shared\Kernel\AbstractFactory;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Client\\{{bundle}}{{store}}\\';

    /**
     * @var string
     */
    protected $application = 'Client';

}
