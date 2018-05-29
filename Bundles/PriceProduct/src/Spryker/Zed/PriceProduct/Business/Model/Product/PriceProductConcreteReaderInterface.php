<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;

interface PriceProductConcreteReaderInterface
{
    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return bool
     */
    public function hasPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesBySkuForCurrentStore($sku, PriceProductDimensionTransfer $priceProductDimensionTransfer);

    /**
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePricesById($idProductConcrete, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function findPriceForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    public function findPriceProductId($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);
}
