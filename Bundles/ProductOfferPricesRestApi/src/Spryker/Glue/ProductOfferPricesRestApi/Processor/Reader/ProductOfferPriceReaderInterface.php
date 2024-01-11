<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ProductOfferPriceReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductOfferPrices(RestRequestInterface $restRequest): RestResponseInterface;

    /**
     * @param array<string> $productOfferReferences
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer|null $productOfferStorageTransfer
     *
     * @return array
     */
    public function getProductOfferPriceRestResources(
        array $productOfferReferences,
        RestRequestInterface $restRequest,
        ?ProductOfferStorageTransfer $productOfferStorageTransfer = null
    ): array;
}
