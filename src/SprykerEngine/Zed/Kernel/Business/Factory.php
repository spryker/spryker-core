<?php

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Shared\Kernel\AbstractFactory;

class Factory extends AbstractFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\{{namespace}}\\Zed\\{{bundle}}{{store}}\\Business\\';

    /**
     * @var array
     */
    protected $baseClasses = [
        'DependencyContainer',
        'Settings'
    ];

}
