<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

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
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[] $priceProductStoreEntities
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mapPriceProductStoreEntitiesToPriceProductTransfers(
        $priceProductStoreEntities
    ): array;
}
