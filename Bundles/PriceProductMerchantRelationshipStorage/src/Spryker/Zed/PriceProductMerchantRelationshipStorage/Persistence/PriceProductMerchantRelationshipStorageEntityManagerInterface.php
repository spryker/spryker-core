<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

interface PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     *
     * @return void
     */
    public function writePriceProductConcrete(
        array $priceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap
    ): void;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransferCollection
     * @param array $existingPriceProductMerchantRelationshipStorageEntityMap
     *
     * @return void
     */
    public function writePriceProductAbstract(
        array $priceProductMerchantRelationshipStorageTransferCollection,
        array $existingPriceProductMerchantRelationshipStorageEntityMap
    ): void;

    /**
     * @api
     *
     * @param int $idMerchantRelationship
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deletePriceProductAbstractByMerchantRelationshipAndIdProductAbstract(
        int $idMerchantRelationship,
        int $idProductAbstract
    ): void;

    /**
     * @api
     *
     * @param int $idMerchantRelationship
     * @param int $idProduct
     *
     * @return void
     */
    public function deletePriceProductConcreteByMerchantRelationshipAndIdProduct(
        int $idMerchantRelationship,
        int $idProduct
    ): void;
}
