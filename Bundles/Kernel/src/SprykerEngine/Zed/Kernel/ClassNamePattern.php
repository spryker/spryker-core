<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

class ClassNamePattern
{

    /**
     * @var string
     */
    const CONTROLLER =
        '\\{{namespace}}\\Zed\\{{bundle}}{{store}}\\Communication\\Controller\\{{controller}}Controller'
    ;

    /**
     * @var string
     */
    const CONTROLLER_WIDGET =
        '\\{{namespace}}\\Zed\\{{bundle}}{{store}}\\Communication\\Controller\\Widget\\{{controller}}Controller'
    ;

}
