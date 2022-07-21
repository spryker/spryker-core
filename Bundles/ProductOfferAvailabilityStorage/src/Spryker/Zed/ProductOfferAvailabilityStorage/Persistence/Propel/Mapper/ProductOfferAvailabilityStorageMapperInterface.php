<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;

interface ProductOfferAvailabilityStorageMapperInterface
{
    /**
     * @var string
     */
    public const COL_ALIAS_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const COL_ALIAS_SKU = 'sku';

    /**
     * @var string
     */
    public const COL_ALIAS_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const COL_ALIAS_ID_STORE = 'id_store';

    /**
     * @var string
     */
    public const COL_ALIAS_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

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
