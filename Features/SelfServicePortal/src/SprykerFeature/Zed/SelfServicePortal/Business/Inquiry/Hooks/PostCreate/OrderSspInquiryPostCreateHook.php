<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class OrderSspInquiryPostCreateHook implements SspInquiryPostCreateHookInterface
{
    public function __construct(protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager)
    {
    }

    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        return $this->selfServicePortalEntityManager->createSspInquirySalesOrder($sspInquiryTransfer);
    }

    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getOrder() && $sspInquiryTransfer->getOrder()->getIdSalesOrder();
    }
}
