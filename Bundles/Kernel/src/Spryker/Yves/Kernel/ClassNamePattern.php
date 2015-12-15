<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Yves\Kernel;

/**
 * Class ClassNamePattern
 */

class ClassNamePattern
{

    /**
     * @var string
     */
    const CONTROLLER =
        '\\{{namespace}}\\Yves\\{{bundle}}{{store}}\\\Controller\\{{controller}}Controller';

}
