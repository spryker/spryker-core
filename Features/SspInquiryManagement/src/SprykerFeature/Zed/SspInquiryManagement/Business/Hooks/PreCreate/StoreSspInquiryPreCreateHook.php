<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class StoreSspInquiryPreCreateHook implements SspInquiryPreCreateHookInterface
{
    /**
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(protected StoreFacadeInterface $storeFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
         $sspInquiryTransfer->setStore($this->storeFacade->getCurrentStore());

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return !$sspInquiryTransfer->getStore() || !$sspInquiryTransfer->getStore()->getIdStore();
    }
}
