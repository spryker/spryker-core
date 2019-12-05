<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;

interface ProductOfferAvailabilityStorageMapperInterface
{
    /**
     * @param array $productOfferAvailabilityRequestData
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer
     */
    public function mapProductOfferAvailabilityRequestDataToRequestTransfer(
        array $productOfferAvailabilityRequestData,
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ProductOfferAvailabilityRequestTransfer;
}
