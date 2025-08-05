<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Symfony\Component\HttpFoundation\Request;

interface SspAssetReaderInterface
{
    public function getSspAssetCollection(
        Request $request,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): SspAssetCollectionTransfer;
}
