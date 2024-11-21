<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Business\Request;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Spryker\Client\KernelApp\KernelAppClientInterface;
use Spryker\Zed\KernelApp\KernelAppConfig;

class Request implements RequestInterface
{
    /**
     * @var \Spryker\Zed\KernelApp\KernelAppConfig
     */
    protected KernelAppConfig $kernelAppConfig;

    /**
     * @var \Spryker\Client\KernelApp\KernelAppClientInterface
     */
    protected KernelAppClientInterface $kernelAppClient;

    /**
     * @var array<\Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface>
     */
    protected array $requestExpanderPlugins;

    /**
     * @param \Spryker\Zed\KernelApp\KernelAppConfig $kernelAppConfig
     * @param \Spryker\Client\KernelApp\KernelAppClientInterface $kernelAppClient
     * @param array<\Spryker\Shared\KernelAppExtension\RequestExpanderPluginInterface> $requestExpanderPlugins
     */
    public function __construct(
        KernelAppConfig $kernelAppConfig,
        KernelAppClientInterface $kernelAppClient,
        array $requestExpanderPlugins
    ) {
        $this->kernelAppConfig = $kernelAppConfig;
        $this->kernelAppClient = $kernelAppClient;
        $this->requestExpanderPlugins = $requestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function request(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer
    {
        $acpHttpRequestTransfer = $this->executeRequestExpanderPlugins($acpHttpRequestTransfer);
        $acpHttpRequestTransfer = $this->expandWithDefaultHeaders($acpHttpRequestTransfer);

        return $this->kernelAppClient->request($acpHttpRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    protected function executeRequestExpanderPlugins(
        AcpHttpRequestTransfer $acpHttpRequestTransfer
    ): AcpHttpRequestTransfer {
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
    public function expandWithDefaultHeaders(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        foreach ($this->kernelAppConfig->getDefaultHeaders() as $headerName => $headerValue) {
            if (isset($acpHttpRequestTransfer->getHeaders()[$headerName])) {
                continue;
            }

            $acpHttpRequestTransfer->addHeader($headerName, $headerValue);
        }

        return $acpHttpRequestTransfer;
    }
}
