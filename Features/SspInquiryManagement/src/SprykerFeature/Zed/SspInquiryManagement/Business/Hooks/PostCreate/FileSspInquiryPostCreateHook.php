<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface;

class FileSspInquiryPostCreateHook implements SspInquiryPostCreateHookInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface $sspInquiryManagementEntityManager
     */
    public function __construct(protected SspInquiryManagementEntityManagerInterface $sspInquiryManagementEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $this->sspInquiryManagementEntityManager->createSspInquiryFiles($sspInquiryTransfer);

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getFiles()->count() > 0;
    }
}
