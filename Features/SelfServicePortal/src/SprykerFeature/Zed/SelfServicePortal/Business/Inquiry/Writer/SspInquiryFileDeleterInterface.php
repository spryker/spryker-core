<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer;

use Generated\Shared\Transfer\FileCollectionTransfer;

interface SspInquiryFileDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return void
     */
    public function deleteSspInquiryFile(FileCollectionTransfer $fileCollectionTransfer): void;
}
