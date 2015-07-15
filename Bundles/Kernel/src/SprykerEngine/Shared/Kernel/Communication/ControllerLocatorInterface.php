<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel\Communication;

use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;

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
