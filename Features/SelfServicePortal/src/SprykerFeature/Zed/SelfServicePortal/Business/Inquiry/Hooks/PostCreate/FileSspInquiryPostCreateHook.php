<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class FileSspInquiryPostCreateHook implements SspInquiryPostCreateHookInterface
{
    public function __construct(protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager)
    {
    }

    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $this->selfServicePortalEntityManager->createSspInquiryFiles($sspInquiryTransfer);

        return $sspInquiryTransfer;
    }

    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getFiles()->count() > 0;
    }
}
