<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\Container;

interface FacadeInterface
{

    /**
     * @param Container $container
     */
    public function setExternalDependencies(Container $container);

}
