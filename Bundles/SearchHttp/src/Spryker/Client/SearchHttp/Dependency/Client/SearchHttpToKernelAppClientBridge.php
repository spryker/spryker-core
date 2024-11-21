<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Dependency\Client;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;

class SearchHttpToKernelAppClientBridge implements SearchHttpToKernelAppClientInterface
{
    /**
     * @var \Spryker\Client\KernelApp\KernelAppClientInterface
     */
    protected $kernelAppClient;

    /**
     * @param \Spryker\Client\KernelApp\KernelAppClientInterface $kernelAppClient
     */
    public function __construct($kernelAppClient)
    {
        $this->kernelAppClient = $kernelAppClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function request(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer
    {
        return $this->kernelAppClient->request($acpHttpRequestTransfer);
    }
}
