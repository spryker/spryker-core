<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel\Fixtures;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\AbstractLocator;

class Locator extends AbstractLocator
{

    /**
     * @var string
     */
    protected $bundle = 'foo';

    /**
     * @var string
     */
    protected $layer = 'bar';

    /**
     * @var string
     */
    protected $application = 'baz';

    /**
     * @param $bundle
     * @param LocatorLocatorInterface $locator
     * @param null|string $className
     *
     * @return object
     */
    public function locate($bundle, LocatorLocatorInterface $locator, $className = null)
    {
        return $this->getFactory($bundle);
    }

}
