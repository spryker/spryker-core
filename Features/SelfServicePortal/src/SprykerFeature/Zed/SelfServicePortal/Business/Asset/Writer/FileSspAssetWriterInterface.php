<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer;

use Generated\Shared\Transfer\SspAssetTransfer;

interface FileSspAssetWriterInterface
{
    public function createFile(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;

    public function updateFile(SspAssetTransfer $sspAssetTransfer): SspAssetTransfer;
}
