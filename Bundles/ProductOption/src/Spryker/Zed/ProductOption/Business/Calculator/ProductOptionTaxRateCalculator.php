<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateWithItemShipmentTaxRateCalculator} instead.
 */
class ProductOptionTaxRateCalculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface $taxFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $queryContainer,
        ProductOptionToTaxFacadeInterface $taxFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $itemTransfers = $this->recalculateByShippingAddressAndItemTransfers($quoteTransfer->getShippingAddress(), $quoteTransfer->getItems());
        $quoteTransfer->setItems($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateForCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $itemTransfers = $this->recalculateByShippingAddressAndItemTransfers($calculableObjectTransfer->getShippingAddress(), $calculableObjectTransfer->getItems());
        $calculableObjectTransfer->setItems($itemTransfers);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function recalculateByShippingAddressAndItemTransfers(?AddressTransfer $shippingAddressTransfer, ArrayObject $itemTransfers): ArrayObject
    {
        $countryIso2Code = $this->getShippingCountryIsoCode($shippingAddressTransfer);
        $productOptionValueIds = $this->getAllProductOptionValueIds($itemTransfers);

        $taxRates = $this->findTaxRatesByIdOptionValueAndCountryIso2Code($productOptionValueIds, $countryIso2Code);

        return $this->setItemsTaxRate($itemTransfers, $taxRates);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     *
     * @return string
     */
    protected function getShippingCountryIsoCode(?AddressTransfer $shippingAddressTransfer): string
    {
        if ($shippingAddressTransfer === null || !$shippingAddressTransfer->getIso2Code()) {
            return $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $shippingAddressTransfer->getIso2Code();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function getAllProductOptionValueIds(ArrayObject $itemTransfers)
    {
        $productOptionValueIds = [];
        foreach ($itemTransfers as $itemTransfer) {
            $productOptionValueIds = array_merge($productOptionValueIds, $this->getProductOptionValueIds($itemTransfer));
        }

        return $productOptionValueIds;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param array $taxRates
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function setItemsTaxRate(ArrayObject $itemTransfers, array $taxRates): ArrayObject
    {
        foreach ($itemTransfers as $itemTransfer) {
            $this->setProductOptionTaxRate($itemTransfer, $taxRates);
        }

        return $itemTransfers;
    }

    /**
     * @param array $taxRates
     * @param int $idOptionValue
     *
     * @return float
     */
    protected function getEffectiveTaxRate(array $taxRates, $idOptionValue)
    {
        foreach ($taxRates as $taxRate) {
            if ((int)$taxRate[ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_VALUE] === $idOptionValue) {
                return (float)$taxRate[ProductOptionQueryContainer::COL_MAX_TAX_RATE];
            }
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int[]
     */
    protected function getProductOptionValueIds(ItemTransfer $itemTransfer)
    {
        $productOptionValueIds = [];
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionValueIds[] = $productOptionTransfer->getIdProductOptionValue();
        }

        return $productOptionValueIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $taxRates
     *
     * @return void
     */
    protected function setProductOptionTaxRate(ItemTransfer $itemTransfer, array $taxRates)
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setTaxRate($this->getEffectiveTaxRate($taxRates, $productOptionTransfer->getIdProductOptionValue()));
        }
    }

    /**
     * @param int[] $productOptionValueIds
     * @param string $countryIso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod[]
     */
    protected function findTaxRatesByIdOptionValueAndCountryIso2Code(array $productOptionValueIds, $countryIso2Code)
    {
        return $this->queryContainer
            ->queryTaxSetByIdProductOptionValueAndCountryIso2Code($productOptionValueIds, $countryIso2Code)
            ->find()
            ->toArray();
    }
}
