<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder;

use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface SspAssetsResponseBuilderInterface
{
    public function createSspAssetCollectionRestResponse(SspAssetCollectionTransfer $sspAssetCollectionTransfer): RestResponseInterface;

    public function createSspAssetRestResponseFromSspAssetCollectionTransfer(
        SspAssetCollectionTransfer $assetCollectionTransfer,
        string $localeName
    ): RestResponseInterface;

    public function createAssetRestResponseFromSspAssetCollectionResponseTransfer(
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer,
        string $localeName
    ): RestResponseInterface;
}
