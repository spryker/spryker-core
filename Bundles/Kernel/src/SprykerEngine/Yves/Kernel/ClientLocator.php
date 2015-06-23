<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel;

use SprykerEngine\Shared\Kernel\AbstractClientLocator;

class ClientLocator extends AbstractClientLocator
{

    /**
     * @var string
     */
    protected $factoryClassNamePattern = '\\{{namespace}}\\Sdk\\Kernel\\Factory';
}
