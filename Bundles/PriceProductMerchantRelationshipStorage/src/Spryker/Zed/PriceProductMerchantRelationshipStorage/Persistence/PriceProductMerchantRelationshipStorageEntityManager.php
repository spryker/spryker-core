<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence;

use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage;
use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStoragePersistenceFactory getFactory()
 */
class PriceProductMerchantRelationshipStorageEntityManager extends AbstractEntityManager implements PriceProductMerchantRelationshipStorageEntityManagerInterface
{
    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage $priceProductAbstractMerchantRelationshipStorageEntity
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function updatePriceProductAbstract(
        SpyPriceProductAbstractMerchantRelationshipStorage $priceProductAbstractMerchantRelationshipStorageEntity,
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        $priceProductAbstractMerchantRelationshipStorageEntity
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function createPriceProductAbstract(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        (new SpyPriceProductAbstractMerchantRelationshipStorage())
            ->setFkProductAbstract($priceProductMerchantRelationshipStorageTransfer->getIdProduct())
            ->setFkCompanyBusinessUnit($priceProductMerchantRelationshipStorageTransfer->getIdCompanyBusinessUnit())
            ->setPriceKey($priceProductMerchantRelationshipStorageTransfer->getPriceKey())
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param \Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductConcreteMerchantRelationshipStorage $priceProductConcreteMerchantRelationshipStorageEntity
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function updatePriceProductConcrete(
        SpyPriceProductConcreteMerchantRelationshipStorage $priceProductConcreteMerchantRelationshipStorageEntity,
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        $priceProductConcreteMerchantRelationshipStorageEntity
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return void
     */
    public function createPriceProductConcrete(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
    ): void {
        (new SpyPriceProductConcreteMerchantRelationshipStorage())
            ->setFkProduct($priceProductMerchantRelationshipStorageTransfer->getIdProduct())
            ->setFkCompanyBusinessUnit($priceProductMerchantRelationshipStorageTransfer->getIdCompanyBusinessUnit())
            ->setPriceKey($priceProductMerchantRelationshipStorageTransfer->getPriceKey())
            ->setData($this->formatData($priceProductMerchantRelationshipStorageTransfer))
            ->setIsSendingToQueue($this->getFactory()->getConfig()->isSendingToQueue())
            ->save();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return array
     */
    protected function formatData(PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer): array
    {
        return [
            'prices' => $priceProductMerchantRelationshipStorageTransfer->getPrices(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     *
     * @return string
     */
    protected function buildPriceKey(PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer): string
    {
        return $this->getFactory()
            ->createPriceKeyGenerator()
            ->buildPriceKey(
                $priceProductMerchantRelationshipStorageTransfer->getStoreName(),
                $priceProductMerchantRelationshipStorageTransfer->getIdProduct(),
                $priceProductMerchantRelationshipStorageTransfer->getIdCompanyBusinessUnit()
            );
    }
}
