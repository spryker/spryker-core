<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Writer;

use Generated\Shared\Transfer\FileCollectionTransfer;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface;

class SspAssetFileDeleter implements SspAssetFileDeleterInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface $sspInquiryManagementEntityManager
     */
    public function __construct(protected SspInquiryManagementEntityManagerInterface $sspInquiryManagementEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return void
     */
    public function deleteSspAssetFile(FileCollectionTransfer $fileCollectionTransfer): void
    {
        $this->sspInquiryManagementEntityManager->deleteSspInquiryFile($fileCollectionTransfer);
    }
}
