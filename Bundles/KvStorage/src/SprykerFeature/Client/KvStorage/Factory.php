<?php

namespace SprykerFeature\Client\KvStorage;

use SprykerEngine\Shared\Kernel\AbstractFactory;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Client\\{{bundle}}{{store}}\\Storage\\';

}
