<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetStorageTransfer;

interface SspAssetStorageReaderInterface
{
    public function findSspAssetStorageByReference(CompanyUserTransfer $companyUserTransfer, string $assetReference): ?SspAssetStorageTransfer;
}
