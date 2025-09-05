<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper;

use Generated\Shared\Transfer\RestSspAssetsAttributesTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface SspAssetsMapperInterface
{
    public function mapRestRequestToSspAssetCriteriaTransfer(
        RestRequestInterface $restRequest
    ): SspAssetCriteriaTransfer;

    public function mapRestRequestToSspAssetCollectionRequestTransfer(
        RestRequestInterface $restRequest,
        RestSspAssetsAttributesTransfer $restSspAssetsAttributesTransfer
    ): SspAssetCollectionRequestTransfer;
}
