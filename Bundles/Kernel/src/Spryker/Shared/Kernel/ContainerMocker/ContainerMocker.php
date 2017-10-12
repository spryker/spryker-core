<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ContainerMocker;

use Spryker\Shared\Config\Environment;
use Spryker\Shared\Kernel\ContainerInterface;

trait ContainerMocker
{
    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function overwriteForTesting(ContainerInterface $container)
    {
        if (Environment::isNotTesting()) {
            return $container;
        }

        $containerGlobals = new ContainerGlobals();
        $containerMocks = $containerGlobals->getContainerGlobals(get_class($this));
        if (count($containerMocks) === 0) {
            return $container;
        }

        foreach ($containerMocks as $key => $containerMock) {
            $container[$key] = $containerMock;
        }

        return $container;
    }
}
