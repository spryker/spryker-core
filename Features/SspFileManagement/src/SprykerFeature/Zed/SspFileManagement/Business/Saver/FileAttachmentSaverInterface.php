<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business\Saver;

use Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer;

interface FileAttachmentSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionResponseTransfer
     */
    public function saveFileAttachmentCollection(
        FileAttachmentCollectionRequestTransfer $fileAttachmentCollectionRequestTransfer
    ): FileAttachmentCollectionResponseTransfer;
}
