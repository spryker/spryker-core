<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;

class OrderSspInquiryPreCreateHook implements SspInquiryPreCreateHookInterface
{
    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     */
    public function __construct(protected SalesFacadeInterface $salesFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
         $sspInquiryTransfer->requireOrder();

        $orderTransfer = $this->salesFacade->getCustomerOrderByOrderReference($sspInquiryTransfer->getOrderOrFail());

         $sspInquiryTransfer->setOrder($orderTransfer);

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getType() === 'order';
    }
}
