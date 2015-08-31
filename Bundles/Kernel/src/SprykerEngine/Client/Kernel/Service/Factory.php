<?php

namespace SprykerEngine\Client\Kernel\Service;

use SprykerEngine\Shared\Kernel\AbstractFactory;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Client\\{{bundle}}{{store}}\\Service\\';

    /**
     * @var string
     */
    protected $application = 'Client';

    /**
     * @var string
     */
    protected $layer = 'Service';

}
