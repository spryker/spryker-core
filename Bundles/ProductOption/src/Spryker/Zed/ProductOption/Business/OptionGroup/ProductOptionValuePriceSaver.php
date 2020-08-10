<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionValuePriceSaver implements ProductOptionValuePriceSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $queryContainer
     */
    public function __construct(ProductOptionQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     *
     * @return void
     */
    public function save(ProductOptionValueTransfer $productOptionValueTransfer)
    {
        $priceEntityMap = $this->getPriceEntityMap($productOptionValueTransfer->getIdProductOptionValue());
        foreach ($productOptionValueTransfer->getPrices() as $moneyValueTransfer) {
            $priceEntity = $this->findPriceEntity($priceEntityMap, $moneyValueTransfer);
            if ($priceEntity === null) {
                $this->createPriceEntity($moneyValueTransfer, $productOptionValueTransfer->getIdProductOptionValue());

                continue;
            }
            $this->updatePriceEntity($priceEntity, $moneyValueTransfer);
        }
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return array First level keys are store ids,
     *               second level keys are currency ids,
     *               values are SpyProductOptionValuePrice entities.
     */
    protected function getPriceEntityMap($idProductOptionValue)
    {
        $priceEntityMap = [];
        $priceCollection = $this->queryContainer
            ->queryProductOptionValuePricesByIdProductOptionValue($idProductOptionValue)
            ->find();

        foreach ($priceCollection as $priceEntity) {
            $priceEntityMap[$priceEntity->getFkStore()][$priceEntity->getFkCurrency()] = $priceEntity;
        }

        return $priceEntityMap;
    }

    /**
     * @param array $priceEntityMap
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice|null
     */
    protected function findPriceEntity(array $priceEntityMap, MoneyValueTransfer $moneyValueTransfer)
    {
        if (!isset($priceEntityMap[$moneyValueTransfer->getFkStore()])) {
            return null;
        }
        if (!isset($priceEntityMap[$moneyValueTransfer->getFkStore()][$moneyValueTransfer->getFkCurrency()])) {
            return null;
        }

        return $priceEntityMap[$moneyValueTransfer->getFkStore()][$moneyValueTransfer->getFkCurrency()];
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice $priceEntity
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return void
     */
    protected function updatePriceEntity(SpyProductOptionValuePrice $priceEntity, MoneyValueTransfer $moneyValueTransfer)
    {
        $priceEntity->setGrossPrice($moneyValueTransfer->getGrossAmount());
        $priceEntity->setNetPrice($moneyValueTransfer->getNetAmount());
        $priceEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     * @param int $idProductOptionValue
     *
     * @return void
     */
    protected function createPriceEntity(MoneyValueTransfer $moneyValueTransfer, $idProductOptionValue)
    {
        $priceEntity = new SpyProductOptionValuePrice();
        $priceEntity->fromArray($moneyValueTransfer->toArray());
        $priceEntity->setGrossPrice($moneyValueTransfer->getGrossAmount());
        $priceEntity->setNetPrice($moneyValueTransfer->getNetAmount());
        $priceEntity->setFkProductOptionValue($idProductOptionValue);
        $priceEntity->save();
    }
}
