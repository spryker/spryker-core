<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface;

class ProductOptionValuePriceHydrator implements ProductOptionValuePriceHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var array Keys are currency ids, values are currency transfer objects in array format.
     */
    protected static $currencyBuffer = [];

    /**
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(ProductOptionToCurrencyFacadeInterface $currencyFacade)
    {
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductOption\Persistence\SpyProductOptionValuePrice> $priceCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer>
     */
    public function getMoneyValueCollection(ObjectCollection $priceCollection)
    {
        $prices = new ArrayObject();
        foreach ($priceCollection as $priceEntity) {
            $prices->append(
                (new MoneyValueTransfer())
                    ->fromArray($priceEntity->toArray(), true)
                    ->setIdEntity($priceEntity->getIdProductOptionValuePrice())
                    ->setNetAmount($priceEntity->getNetPrice())
                    ->setGrossAmount($priceEntity->getGrossPrice())
                    ->setCurrency($this->getCurrencyTransferByIdCurrency($priceEntity->getFkCurrency()))
            );
        }

        return $prices;
    }

    /**
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransferByIdCurrency($idCurrency)
    {
        if (!isset(static::$currencyBuffer[$idCurrency])) {
            static::$currencyBuffer[$idCurrency] = $this->currencyFacade
                ->getByIdCurrency($idCurrency)
                ->toArray();
        }

        return (new CurrencyTransfer())->fromArray(static::$currencyBuffer[$idCurrency]);
    }
}
