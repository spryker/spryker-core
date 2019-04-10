<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProduct\Persistence\PriceProductPersistenceFactory getFactory()
 * @method \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer save(\Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer $spyCompanyUnitAddressEntityTransfer)
 */
class PriceProductEntityManager extends AbstractEntityManager implements PriceProductEntityManagerInterface
{
    /**
     * @deprecated Use \Spryker\Zed\PriceProduct\Business\Model\Product\PriceProductStoreWriter::deleteOrphanPriceProductStoreEntities() instead.
     *
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void
    {
        $priceProductStoreQuery = $this->getFactory()
            ->createPriceProductStoreQuery();

        $this->getFactory()
            ->createPriceProductDimensionQueryExpander()
            ->expandPriceProductStoreQueryWithPriceDimensionForDelete(
                $priceProductStoreQuery,
                new PriceProductCriteriaTransfer()
            );

        if ($priceProductStoreQuery->getAsColumns() === 0) {
            return;
        }

        $priceProductStoreQuery->find()->delete();
    }

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deletePriceProductStore(int $idPriceProductStore): void
    {
        $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByIdPriceProductStore($idPriceProductStore)
            ->findOne()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer
     */
    public function savePriceProductDefaultEntity(
        SpyPriceProductDefaultEntityTransfer $spyPriceProductDefaultEntityTransfer
    ): SpyPriceProductDefaultEntityTransfer {
        return $this->save($spyPriceProductDefaultEntityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    public function deletePriceProductStoreByPriceProductTransfer(PriceProductTransfer $priceProductTransfer): void
    {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $this->getFactory()
            ->createPriceProductStoreQuery()
            ->filterByFkCurrency($moneyValueTransfer->getCurrency()->getIdCurrency())
            ->filterByFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->filterByFkStore($moneyValueTransfer->getFkStore())
            ->filterByGrossPrice($moneyValueTransfer->getGrossAmount())
            ->filterByNetPrice($moneyValueTransfer->getNetAmount())
            ->find()
            ->delete();
    }

    /**
     * @param int $idPriceProduct
     *
     * @return void
     */
    public function deletePriceProductById(int $idPriceProduct): void
    {
        $this->getFactory()
            ->createPriceProductQuery()
            ->findByIdPriceProduct($idPriceProduct)
            ->delete();
    }

    /**
     * @param int $idPriceProductDefault
     *
     * @return void
     */
    public function deletePriceProductDefaultById(int $idPriceProductDefault): void
    {
        $this->getFactory()
            ->createPriceProductDefaultQuery()
            ->findByIdPriceProductDefault($idPriceProductDefault)
            ->delete();
    }
}
