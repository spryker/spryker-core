<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipBusinessFactory getFactory()
 */
interface PriceProductMerchantRelationshipFacadeInterface
{
    /**
     * Specification:
     *  - Saves price for given product price configuration
     *  - Creates spy_price_product_store entry if does not exist,
     *  - Creates connection between spy_price_product_store and spy_price_product_business_unit
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePriceProductMerchantRelationship(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;

    /**
     * Specification:
     *  - Deletes connection records between spy_price_product_store and spy_price_product_business_unit by idMerchantRelationship
     *
     * @api
     *
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deletePriceProductMerchantRelationshipByIdMerchantRelationship(int $idMerchantRelationship): void;

    /**
     * Specification:
     *  - Deletes connection records between spy_price_product_store and spy_price_product_business_unit by idPriceProductStore
     *
     * @api
     *
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deletePriceProductMerchantRelationshipByIdPriceProductStore(int $idPriceProductStore): void;

    /**
     * Specification:
     *  - Deletes all connections between spy_price_product_store and spy_price_product_business_unit
     *
     * @api
     *
     * @return void
     */
    public function deleteAllPriceProductMerchantRelationship(): void;

    /**
     * Specification:
     *  - Adds specific dimension type to the PriceProductDimensionTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function expandPriceProductDimension(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer;
}
