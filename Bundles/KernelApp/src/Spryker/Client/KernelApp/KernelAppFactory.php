<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\KernelApp;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\KernelApp\Dependency\External\KernelAppToHttpClientAdapterInterface;
use Spryker\Client\KernelApp\Request\Request;
use Spryker\Client\KernelApp\Request\RequestInterface;

/**
 * @method \Spryker\Client\KernelApp\KernelAppConfig getConfig()
 */
class KernelAppFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\KernelApp\Request\RequestInterface
     */
    public function createRequest(): RequestInterface
    {
        return new Request(
            $this->getConfig(),
            $this->getHttpClient(),
            $this->getRequestExpanderPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface>
     */
    public function getRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(KernelAppDependencyProvider::REQUEST_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\KernelApp\Dependency\External\KernelAppToHttpClientAdapterInterface
     */
    public function getHttpClient(): KernelAppToHttpClientAdapterInterface
    {
        return $this->getProvidedDependency(KernelAppDependencyProvider::CLIENT_HTTP);
    }
}
