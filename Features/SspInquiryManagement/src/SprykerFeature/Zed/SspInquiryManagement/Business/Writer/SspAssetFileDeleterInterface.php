<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Writer;

use Generated\Shared\Transfer\FileCollectionTransfer;

interface SspAssetFileDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileCollectionTransfer $fileCollectionTransfer
     *
     * @return void
     */
    public function deleteSspAssetFile(FileCollectionTransfer $fileCollectionTransfer): void;
}
