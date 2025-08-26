<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface SspAssetStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $assetReference
     *
     * @return array<string, mixed>|null
     */
    public function getSspAssetDataByReference(CompanyUserTransfer $companyUserTransfer, string $assetReference): ?array;
}
