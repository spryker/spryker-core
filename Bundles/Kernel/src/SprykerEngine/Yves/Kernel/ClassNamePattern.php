<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

/**
 * Class ClassNamePattern
 */
class ClassNamePattern
{

    /**
     * @var string
     */
    const CONTROLLER =
        '\\{{namespace}}\\Yves\\{{bundle}}{{store}}\\Communication\\Controller\\{{controller}}Controller';

}
