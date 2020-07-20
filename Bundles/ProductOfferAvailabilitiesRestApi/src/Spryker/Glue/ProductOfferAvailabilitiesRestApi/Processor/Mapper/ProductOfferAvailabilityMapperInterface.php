<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer;
use Generated\Shared\Transfer\RestProductOfferAvailabilitiesAttributesTransfer;

interface ProductOfferAvailabilityMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer
     * @param \Generated\Shared\Transfer\RestProductOfferAvailabilitiesAttributesTransfer $restProductOfferAvailabilitiesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferAvailabilitiesAttributesTransfer
     */
    public function mapProductOfferAvailabilityStorageTransferToRestProductOfferAvailabilitiesAttributesTransfer(
        ProductOfferAvailabilityStorageTransfer $productOfferAvailabilityStorageTransfer,
        RestProductOfferAvailabilitiesAttributesTransfer $restProductOfferAvailabilitiesAttributesTransfer
    ): RestProductOfferAvailabilitiesAttributesTransfer;
}
