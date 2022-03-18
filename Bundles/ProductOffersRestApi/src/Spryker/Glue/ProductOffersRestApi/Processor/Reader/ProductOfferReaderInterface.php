<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi\Processor\Reader;

interface ProductOfferReaderInterface
{
 /**
  * @param array<string> $productOfferReferences
  *
  * @return array<array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
  */
    public function getProductOfferResourcesByProductOfferReferences(array $productOfferReferences): array;
}
