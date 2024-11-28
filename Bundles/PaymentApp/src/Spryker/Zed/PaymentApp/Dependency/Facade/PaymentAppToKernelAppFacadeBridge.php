<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentApp\Dependency\Facade;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;

class PaymentAppToKernelAppFacadeBridge implements PaymentAppToKernelAppFacadeInterface
{
    /**
     * @var \Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface
     */
    protected $kernelAppFacade;

    /**
     * @param \Spryker\Zed\KernelApp\Business\KernelAppFacadeInterface $kernelAppFacade
     */
    public function __construct($kernelAppFacade)
    {
        $this->kernelAppFacade = $kernelAppFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function makeRequest(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer
    {
        return $this->kernelAppFacade->makeRequest($acpHttpRequestTransfer);
    }
}
