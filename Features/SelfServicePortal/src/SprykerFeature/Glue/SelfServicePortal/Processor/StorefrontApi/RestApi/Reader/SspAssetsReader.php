<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Builder\SspAssetsResponseBuilderInterface;
use SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper\SspAssetsMapperInterface;

class SspAssetsReader implements SspAssetsReaderInterface
{
    public function __construct(
        protected SelfServicePortalClientInterface $selfServicePortalClient,
        protected SspAssetsResponseBuilderInterface $restSspAssetsResponseBuilder,
        protected SspAssetsMapperInterface $sspAssetsMapper
    ) {
    }

    public function getSspAsset(RestRequestInterface $restRequest): RestResponseInterface
    {
        $localeName = $restRequest->getMetadata()->getLocale();
        $sspAssetCriteriaTransfer = $this->sspAssetsMapper->mapRestRequestToSspAssetCriteriaTransfer($restRequest);
        $sspAssetCollectionTransfer = $this->selfServicePortalClient->getSspAssetCollection($sspAssetCriteriaTransfer);

        return $this->restSspAssetsResponseBuilder->createSspAssetRestResponseFromSspAssetCollectionTransfer($sspAssetCollectionTransfer, $localeName);
    }

    public function getSspAssets(RestRequestInterface $restRequest): RestResponseInterface
    {
        $sspAssetCriteriaTransfer = $this->sspAssetsMapper->mapRestRequestToSspAssetCriteriaTransfer($restRequest);

        $sspAssetCollectionTransfer = $this->selfServicePortalClient->getSspAssetCollection($sspAssetCriteriaTransfer);

        return $this->restSspAssetsResponseBuilder->createSspAssetCollectionRestResponse($sspAssetCollectionTransfer);
    }
}
