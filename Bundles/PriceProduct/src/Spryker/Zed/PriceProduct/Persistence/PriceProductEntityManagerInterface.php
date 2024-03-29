<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 */
interface PriceProductEntityManagerInterface
{
    /**
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void;

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deletePriceProductStore(int $idPriceProductStore): void;

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer
     */
    public function savePriceProductDefaultEntity(
        SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
    ): SpyPriceProductDefaultEntityTransfer;

    /**
     * @param int $idPriceProduct
     *
     * @return void
     */
    public function deletePriceProductById(int $idPriceProduct): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function deletePriceProductStoreByPriceProductTransfer(PriceProductTransfer $priceProductTransfer): void;

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deletePriceProductDefaultsByPriceProductStoreId(int $idPriceProductStore): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePriceProductDefaults(PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int
     */
    public function savePriceProductForProductConcrete(PriceProductTransfer $priceProductTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return int
     */
    public function savePriceProductForProductAbstract(PriceProductTransfer $priceProductTransfer): int;
}
