<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;

class PriceProductStoreWriter implements PriceProductStoreWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $priceProductEntityManager;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     */
    public function __construct(
        PriceProductQueryContainerInterface $priceProductQueryContainer,
        PriceProductEntityManagerInterface $priceProductEntityManager
    ) {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
        $this->priceProductEntityManager = $priceProductEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function persistPriceProductStore(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $moneyValueTransfer
            ->requireFkCurrency()
            ->requireFkStore();

        $priceProduceStoreEntity = $this->findPriceProductStoreEntity(
            $priceProductTransfer,
            $moneyValueTransfer
        );

        $priceProduceStoreEntity->fromArray($moneyValueTransfer->toArray());

        $priceProduceStoreEntity
            ->setGrossPrice($moneyValueTransfer->getGrossAmount())
            ->setNetPrice($moneyValueTransfer->getNetAmount())
            ->setFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->save();

        $moneyValueTransfer->setIdEntity($priceProduceStoreEntity->getIdPriceProductStore());

        return $priceProductTransfer;
    }

    /**
     * @return void
     */
    public function deleteOrphanPriceProductStoreEntities(): void
    {
        $this->priceProductEntityManager->deleteOrphanPriceProductStoreEntities();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $moneyValueTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    protected function findPriceProductStoreEntity(
        PriceProductTransfer $priceProductTransfer,
        PriceProductTransfer $moneyValueTransfer
    ): SpyPriceProductStore {

        return $this->priceProductQueryContainer
            ->queryPriceProductStoreByProductCurrencyStore(
                $priceProductTransfer->getIdPriceProduct(),
                $moneyValueTransfer->getFkCurrency(),
                $moneyValueTransfer->getFkStore()
            )
            ->filterByNetPrice($moneyValueTransfer->getNetAmount())
            ->filterByGrossPrice($moneyValueTransfer->getGrossAmount())
            ->findOneOrCreate();
    }
}
