<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Builder\SspAssetsResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Mapper\SspAssetsMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;

class SspAssetsReader implements SspAssetsReaderInterface
{
    public function __construct(
        protected SelfServicePortalFacadeInterface $selfServicePortalFacade,
        protected SspAssetsResponseBuilderInterface $sspAssetsResponseBuilder,
        protected SspAssetsMapperInterface $sspAssetsMapper
    ) {
    }

    public function getSspAssetCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $sspAssetCriteriaTransfer = $this->sspAssetsMapper->mapGlueRequestToSspAssetCriteriaTransfer($glueRequestTransfer);
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        return $this->sspAssetsResponseBuilder->createSspAssetCollectionResponse($sspAssetCollectionTransfer, $glueRequestTransfer);
    }

    public function getSspAsset(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $sspAssetCriteriaTransfer = $this->sspAssetsMapper->mapGlueRequestToSspAssetCriteriaTransfer($glueRequestTransfer);
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);

        if ($sspAssetCollectionTransfer->getSspAssets()->count() === 0) {
            return $this->sspAssetsResponseBuilder->createAssetNotFoundErrorResponse($glueRequestTransfer->getLocaleOrFail());
        }

        $sspAssetTransfer = $sspAssetCollectionTransfer->getSspAssets()->getIterator()->current();

        return $this->sspAssetsResponseBuilder->createSspAssetResponse($sspAssetTransfer, $glueRequestTransfer);
    }
}
