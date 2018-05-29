<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProduct;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;

interface PriceProductMapperInterface
{
    /**
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore $priceProductStoreEntity
     * @param \Orm\Zed\PriceProduct\Persistence\SpyPriceProduct $priceProductEntity
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapProductPriceTransfer(SpyPriceProductStore $priceProductStoreEntity, SpyPriceProduct $priceProductEntity);

    /**
     * @return string
     */
    public function getGrossPriceModeIdentifier();

    /**
     * @return string
     */
    public function getNetPriceModeIdentifier();

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[] $priceProductStoreTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStoreEntityTransfersToPriceProduct(
        array $priceProductStoreTransferCollection,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): array;
}
