<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantApp\Business\Request;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantUser\Business\Exception\CurrentMerchantUserNotFoundException;

class Request implements RequestInterface
{
    /**
     * @var \Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeInterface
     */
    protected MerchantAppToMerchantUserFacadeInterface $merchantUserFacade;

    /**
     * @param \Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(MerchantAppToMerchantUserFacadeInterface $merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpRequestTransfer
     */
    public function addMerchantReferenceHeader(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpRequestTransfer
    {
        try {
            $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
            $merchantTransfer = $merchantUserTransfer->getMerchant();

            if (!$merchantTransfer) {
                return $acpHttpRequestTransfer;
            }

            $acpHttpRequestTransfer = $acpHttpRequestTransfer->addHeader('x-merchant-reference', $merchantTransfer->getMerchantReferenceOrFail());
        } catch (CurrentMerchantUserNotFoundException) {
        }

        return $acpHttpRequestTransfer;
    }
}
