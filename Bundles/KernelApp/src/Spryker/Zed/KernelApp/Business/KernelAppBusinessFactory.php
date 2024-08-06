<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Business;

use Spryker\Client\KernelApp\KernelAppClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\KernelApp\Business\Request\Request;
use Spryker\Zed\KernelApp\KernelAppDependencyProvider;

/**
 * @method \Spryker\Zed\KernelApp\Persistence\KernelAppEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\KernelApp\KernelAppConfig getConfig()
 */
class KernelAppBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\KernelApp\Business\Request\Request
     */
    public function createRequest(): Request
    {
        return new Request(
            $this->getConfig(),
            $this->getKernelAppClient(),
            $this->getRequestExpanderPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\KernelApp\KernelAppClientInterface
     */
    public function getKernelAppClient(): KernelAppClientInterface
    {
        return $this->getProvidedDependency(KernelAppDependencyProvider::CLIENT_KERNEL_APP);
    }

    /**
     * @return array<\Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface>
     */
    public function getRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(KernelAppDependencyProvider::REQUEST_EXPANDER_PLUGINS);
    }
}
