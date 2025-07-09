<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader;

use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;

interface CompanyFileReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer;
}
