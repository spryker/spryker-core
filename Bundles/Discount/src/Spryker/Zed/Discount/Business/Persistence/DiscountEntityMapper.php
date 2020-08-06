<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToCurrencyInterface;

class DiscountEntityMapper implements DiscountEntityMapperInterface
{
    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected static $currencyCache = [];

    /**
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToCurrencyInterface $currencyFacade
     */
    public function __construct(DiscountToCurrencyInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer
     */
    public function mapFromEntity(SpyDiscount $discountEntity)
    {
        $discountTransfer = new DiscountTransfer();
        $discountTransfer->fromArray($discountEntity->toArray(), true);
        $discountTransfer->setMoneyValueCollection($this->getMoneyValueCollectionForEntity($discountEntity));

        return $discountTransfer;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MoneyValueTransfer[]
     */
    public function getMoneyValueCollectionForEntity(SpyDiscount $discountEntity)
    {
        $moneyValueCollection = new ArrayObject();
        foreach ($discountEntity->getDiscountAmounts() as $discountMoneyAmountEntity) {
            $moneyValueTransfer = new MoneyValueTransfer();
            $moneyValueTransfer->fromArray($discountMoneyAmountEntity->toArray(), true);
            $moneyValueTransfer->setFkStore($discountEntity->getFkStore());
            $moneyValueTransfer->setIdEntity($discountMoneyAmountEntity->getPrimaryKey());

            $currencyTransfer = $this->getCurrencyByIdCurrency(
                $discountMoneyAmountEntity->getFkCurrency()
            );
            $moneyValueTransfer->setCurrency($currencyTransfer);

            $moneyValueCollection->append($moneyValueTransfer);
        }

        return $moneyValueCollection;
    }

    /**
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrencyByIdCurrency($idCurrency)
    {
        if (isset(static::$currencyCache[$idCurrency])) {
            return static::$currencyCache[$idCurrency];
        }

        static::$currencyCache[$idCurrency] = $this->currencyFacade->getByIdCurrency($idCurrency);

        return static::$currencyCache[$idCurrency];
    }
}
