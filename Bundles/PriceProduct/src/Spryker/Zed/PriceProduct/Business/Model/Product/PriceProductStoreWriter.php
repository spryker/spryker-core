<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\Product;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;

class PriceProductStoreWriter implements PriceProductStoreWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     */
    public function __construct(PriceProductQueryContainerInterface $priceProductQueryContainer)
    {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore
     */
    public function persistPriceProductStore(PriceProductTransfer $priceProductTransfer)
    {
        $priceProductTransfer->requireMoneyValue();

        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

        $priceProduceStoreEntity = $this->priceProductQueryContainer
            ->queryPriceProductStoreByProductCurrencyStore(
                $priceProductTransfer->getIdPriceProduct(),
                $moneyValueTransfer->getFkCurrency(),
                $moneyValueTransfer->getFkStore()
            )->findOneOrCreate();

        $priceProduceStoreEntity->fromArray($moneyValueTransfer->toArray());

        $priceProduceStoreEntity
            ->setFkPriceProduct($priceProductTransfer->getIdPriceProduct())
            ->setNetPrice($moneyValueTransfer->getNetAmount())
            ->setGrossPrice($moneyValueTransfer->getGrossAmount())
            ->save();

        $moneyValueTransfer->setIdEntity($priceProduceStoreEntity->getIdPriceProductStore());

        return $priceProduceStoreEntity;
    }
}
