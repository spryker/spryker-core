<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\Communication;

use Spryker\Shared\Kernel\LocatorLocatorInterface;

interface ControllerLocatorInterface
{

    /**
     * @param \Pimple $application
     * @param LocatorLocatorInterface $locator
     *
     * @return object
     */
    public function locate(\Pimple $application, LocatorLocatorInterface $locator);

    /**
     * @return bool
     */
    public function canLocate();

}
