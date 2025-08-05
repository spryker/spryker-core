<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Deleter;

use Generated\Shared\Transfer\FileCollectionTransfer;

interface SspAssetManagementFileDeleterInterface
{
    public function deleteSspAssetRelationsByFileCollection(FileCollectionTransfer $fileCollectionTransfer): FileCollectionTransfer;
}
