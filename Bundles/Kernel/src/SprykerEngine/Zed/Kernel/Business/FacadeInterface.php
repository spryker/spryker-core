<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Container;

interface FacadeInterface
{

    /**
     * @param Container $container
     */
    public function setExternalDependencies(Container $container);

}
