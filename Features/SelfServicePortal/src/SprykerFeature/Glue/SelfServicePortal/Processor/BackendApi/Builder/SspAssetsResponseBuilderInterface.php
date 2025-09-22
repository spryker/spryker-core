<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Builder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;

interface SspAssetsResponseBuilderInterface
{
    public function createSspAssetCollectionResponse(
        SspAssetCollectionTransfer $sspAssetCollectionTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;

    public function createSspAssetResponse(
        SspAssetTransfer $sspAssetTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer;

    public function createAssetNotFoundErrorResponse(string $localeName): GlueResponseTransfer;

    public function createErrorResponseFromAssetCollectionResponse(
        SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer,
        string $localeName
    ): GlueResponseTransfer;
}
