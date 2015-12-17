<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Kernel\Communication;

interface ControllerLocatorInterface
{

    /**
     * @return object
     */
    public function locate();

    /**
     * @return bool
     */
    public function canLocate();

}
