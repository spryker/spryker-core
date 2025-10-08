<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Builder\SspAssetsResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Mapper\SspAssetsMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;

class SspAssetsUpdater implements SspAssetsUpdaterInterface
{
    public function __construct(
        protected SelfServicePortalFacadeInterface $selfServicePortalFacade,
        protected SspAssetsResponseBuilderInterface $sspAssetsResponseBuilder,
        protected SspAssetsMapperInterface $sspAssetsMapper
    ) {
    }

    public function updateSspAsset(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $sspAssetCollectionRequestTransfer = $this->sspAssetsMapper->mapGlueRequestToSspAssetCollectionRequestTransferForUpdate($glueRequestTransfer);

        if (!$sspAssetCollectionRequestTransfer->getSspAssets()->count()) {
            return $this->sspAssetsResponseBuilder->createAssetNotFoundErrorResponse($glueRequestTransfer->getLocaleOrFail());
        }

        $sspAssetCollectionResponseTransfer = $this->selfServicePortalFacade->updateSspAssetCollection($sspAssetCollectionRequestTransfer);

        if ($sspAssetCollectionResponseTransfer->getErrors()->count() > 0) {
            return $this->sspAssetsResponseBuilder->createErrorResponseFromAssetCollectionResponse($sspAssetCollectionResponseTransfer, $glueRequestTransfer->getLocaleOrFail());
        }

        $sspAssetTransfer = $sspAssetCollectionResponseTransfer->getSspAssets()->getIterator()->current();

        return $this->sspAssetsResponseBuilder->createSspAssetResponse($sspAssetTransfer, $glueRequestTransfer);
    }
}
