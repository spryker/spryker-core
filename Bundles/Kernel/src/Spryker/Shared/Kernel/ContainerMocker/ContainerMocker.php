<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ContainerMocker;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Shared\Kernel\KernelConstants;

trait ContainerMocker
{
    /**
     * @param \Spryker\Shared\Kernel\ContainerInterface $container
     *
     * @return \Spryker\Shared\Kernel\ContainerInterface
     */
    protected function overwriteForTesting(ContainerInterface $container)
    {
        if (!$this->isContainerOverridingEnabled()) {
            return $container;
        }

        $containerGlobals = new ContainerGlobals();
        $containerMocks = $containerGlobals->getContainerGlobals(static::class);
        if (count($containerMocks) === 0) {
            return $container;
        }

        foreach ($containerMocks as $key => $containerMock) {
            $container[$key] = $containerMock;
        }

        return $container;
    }

    /**
     * @return bool
     */
    protected function isContainerOverridingEnabled(): bool
    {
        return Config::get(KernelConstants::ENABLE_CONTAINER_OVERRIDING, $this->getOverwriteContainerForTestingDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    protected function getOverwriteContainerForTestingDefaultValue(): bool
    {
        return APPLICATION_ENV === 'devtest';
    }
}
