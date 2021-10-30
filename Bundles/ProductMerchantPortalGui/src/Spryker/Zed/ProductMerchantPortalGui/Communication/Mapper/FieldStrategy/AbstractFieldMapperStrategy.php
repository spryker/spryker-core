<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\FieldStrategy;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;

abstract class AbstractFieldMapperStrategy implements FieldMapperStrategyInterface
{
    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    protected const PRICE_DIMENSION_TYPE_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * @uses \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_TYPE_DEFAULT
     *
     * @var string
     */
    protected const PRICE_TYPE_DEFAULT = 'DEFAULT';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param string $priceTypeName
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createNewPriceProduct(
        string $priceTypeName,
        PriceProductTransfer $priceProductTransfer
    ): PriceProductTransfer {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        $newPriceProductTransfer = (new PriceProductTransfer())
            ->setMoneyValue($this->recreateMoneyValueTransfer($moneyValueTransfer));

        $priceTypeTransfer = $this->getPriceTypeByName($priceTypeName);
        if ($priceTypeTransfer) {
            $newPriceProductTransfer->setPriceType($priceTypeTransfer)
                ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                ->setPriceDimension(
                    (new PriceProductDimensionTransfer())
                        ->setType(static::PRICE_DIMENSION_TYPE_DEFAULT),
                );
        }

        return $newPriceProductTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function expandPriceProductTransfersWithTypes(ArrayObject $priceProductTransfers): ArrayObject
    {
        $priceTypeIds = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceTypeIds[] = $priceProductTransfer->getFkPriceType();
        }

        /** @var \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer */
        $priceProductTransfer = $priceProductTransfers[0];

        $moneyValueTransfer = $priceProductTransfer->getMoneyValueOrFail();

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            if (in_array($priceTypeTransfer->getIdPriceType(), $priceTypeIds)) {
                continue;
            }

            $priceProductTransfers[] = (new PriceProductTransfer())
                ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                ->setPriceType($priceTypeTransfer)
                ->setPriceDimension(
                    (new PriceProductDimensionTransfer())->setType(static::PRICE_DIMENSION_TYPE_DEFAULT),
                )
                ->setMoneyValue($this->recreateMoneyValueTransfer($moneyValueTransfer));
        }

        return $priceProductTransfers;
    }

    /**
     * @param string $priceTypeName
     *
     * @return \Generated\Shared\Transfer\PriceTypeTransfer|null
     */
    protected function getPriceTypeByName(string $priceTypeName): ?PriceTypeTransfer
    {
        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            if ($priceTypeTransfer->getNameOrFail() === $priceTypeName) {
                return $priceTypeTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer
     */
    protected function recreateMoneyValueTransfer(MoneyValueTransfer $moneyValueTransfer): MoneyValueTransfer
    {
        return (new MoneyValueTransfer())
            ->setCurrency($moneyValueTransfer->getCurrency())
            ->setFkStore($moneyValueTransfer->getFkStore())
            ->setStore($moneyValueTransfer->getStore())
            ->setFkCurrency($moneyValueTransfer->getFkCurrency())
            ->setIdEntity($moneyValueTransfer->getIdEntity());
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    protected function findDefaultPriceProduct(ArrayObject $priceProductTransfers): ?PriceProductTransfer
    {
        foreach ($priceProductTransfers as $priceProductTransfer) {
            $priceTypeName = $priceProductTransfer
                ->getPriceTypeOrFail()
                ->getNameOrFail();

            if ($priceTypeName === static::PRICE_TYPE_DEFAULT) {
                return $priceProductTransfer;
            }
        }

        return null;
    }
}
