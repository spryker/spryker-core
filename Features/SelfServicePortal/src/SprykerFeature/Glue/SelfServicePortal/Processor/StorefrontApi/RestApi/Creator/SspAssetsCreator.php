<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Creator;

use Generated\Shared\Transfer\RestSspAssetsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspAssetsResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspAssetsMapperInterface;

class SspAssetsCreator implements SspAssetsCreatorInterface
{
    public function __construct(
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected SspAssetsMapperInterface $assetsMapper,
        protected SspAssetsResponseBuilderInterface $restAssetsResponseBuilder
    ) {
    }

    public function create(
        RestRequestInterface $restRequest,
        RestSspAssetsAttributesTransfer $restSspAssetsAttributesTransfer
    ): RestResponseInterface {
        $localeName = $restRequest->getMetadata()->getLocale();

        $sspAssetCollectionRequestTransfer = $this->assetsMapper
            ->mapRestRequestToSspAssetCollectionRequestTransfer($restRequest, $restSspAssetsAttributesTransfer);

        $sspAssetCollectionResponseTransfer = $this->selfServicePortalClient
            ->createSspAssetCollection($sspAssetCollectionRequestTransfer);

        return $this->restAssetsResponseBuilder
            ->createAssetRestResponseFromSspAssetCollectionResponseTransfer($sspAssetCollectionResponseTransfer, $localeName);
    }
}
