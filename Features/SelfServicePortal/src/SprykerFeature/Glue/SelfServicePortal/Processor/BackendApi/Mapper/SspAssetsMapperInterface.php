<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Mapper;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;

interface SspAssetsMapperInterface
{
    public function mapGlueRequestToSspAssetCriteriaTransfer(GlueRequestTransfer $glueRequestTransfer): SspAssetCriteriaTransfer;

    public function mapGlueRequestToSspAssetCollectionRequestTransferForCreate(GlueRequestTransfer $glueRequestTransfer): SspAssetCollectionRequestTransfer;

    public function mapGlueRequestToSspAssetCollectionRequestTransferForUpdate(GlueRequestTransfer $glueRequestTransfer): SspAssetCollectionRequestTransfer;

    public function mapSspAssetTransferToSspAssetsBackendApiAttributesTransfer(
        SspAssetTransfer $sspAssetTransfer
    ): SspAssetsBackendApiAttributesTransfer;
}
