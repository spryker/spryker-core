<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class StoreSspInquiryPreCreateHook implements SspInquiryPreCreateHookInterface
{
    public function __construct(protected StoreFacadeInterface $storeFacade)
    {
    }

    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
         $sspInquiryTransfer->setStore($this->storeFacade->getCurrentStore());

        return $sspInquiryTransfer;
    }

    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return !$sspInquiryTransfer->getStore() || !$sspInquiryTransfer->getStore()->getIdStore();
    }
}
