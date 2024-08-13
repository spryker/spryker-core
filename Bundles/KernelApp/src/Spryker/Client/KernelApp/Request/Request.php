<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\KernelApp\Request;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Spryker\Client\KernelApp\Dependency\External\KernelAppToHttpClientAdapterInterface;
use Spryker\Client\KernelApp\KernelAppConfig;

class Request implements RequestInterface
{
    /**
     * @var array<\Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface>
     */
    protected array $requestExpanderPlugins;

    protected KernelAppConfig $config;

    /**
     * @var \Spryker\Client\KernelApp\Dependency\External\KernelAppToHttpClientAdapterInterface
     */
    protected KernelAppToHttpClientAdapterInterface $httpClient;

    /**
     * @param \Spryker\Client\KernelApp\KernelAppConfig $config
     * @param \Spryker\Client\KernelApp\Dependency\External\KernelAppToHttpClientAdapterInterface $httpClient
     * @param array<\Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface> $acpRequestExpanderPlugins
     */
    public function __construct(KernelAppConfig $config, KernelAppToHttpClientAdapterInterface $httpClient, array $acpRequestExpanderPlugins)
    {
        $this->config = $config;
        $this->requestExpanderPlugins = $acpRequestExpanderPlugins;
        $this->httpClient = $httpClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function request(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer
    {
        $acpHttpRequestTransfer = $this->executeRequestExpanderPlugins($acpHttpRequestTransfer);
        $acpHttpRequestTransfer = $this->expandRequest($acpHttpRequestTransfer);

        return $this->httpClient->send($acpHttpRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    protected function executeRequestExpanderPlugins(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        foreach ($this->requestExpanderPlugins as $acpRequestExpanderPlugin) {
            $acpHttpRequestTransfer = $acpRequestExpanderPlugin->expandRequest($acpHttpRequestTransfer);
        }

        return $acpHttpRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    protected function expandRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        return $this->addTenantIdentifier($acpHttpRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    protected function addTenantIdentifier(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        return $acpHttpRequestTransfer->addHeader('x-tenant-identifier', $this->config->getTenantIdentifier());
    }
}
