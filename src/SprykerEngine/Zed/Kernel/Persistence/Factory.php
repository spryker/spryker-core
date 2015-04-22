<?php

namespace SprykerEngine\Zed\Kernel\Persistence;

use SprykerEngine\Shared\Kernel\AbstractFactory;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Zed\\{{bundle}}{{store}}\\Persistence\\';

    /**
     * @var array
     */
    protected $baseClasses = [
        'DependencyContainer',
        'QueryContainer'
    ];
}
