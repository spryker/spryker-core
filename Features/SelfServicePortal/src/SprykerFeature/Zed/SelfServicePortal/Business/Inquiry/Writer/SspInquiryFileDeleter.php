<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer;

use Generated\Shared\Transfer\FileCollectionTransfer;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class SspInquiryFileDeleter implements SspInquiryFileDeleterInterface
{
    public function __construct(protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager)
    {
    }

    public function deleteSspInquiryFile(FileCollectionTransfer $fileCollectionTransfer): void
    {
        $this->selfServicePortalEntityManager->deleteSspInquiryFileRelation($fileCollectionTransfer);
    }
}
