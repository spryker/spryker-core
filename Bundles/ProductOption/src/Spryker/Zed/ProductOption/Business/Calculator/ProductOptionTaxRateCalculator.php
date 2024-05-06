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
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface;
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
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface
     */
    protected ProductOptionToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface $taxFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $queryContainer,
        ProductOptionToTaxFacadeInterface $taxFacade,
        ProductOptionToStoreFacadeInterface $storeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->taxFacade = $taxFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $itemTransfers = $this->recalculateByShippingAddressAndItemTransfers($quoteTransfer->getShippingAddress(), $quoteTransfer->getItems(), $quoteTransfer->getStore());
        $quoteTransfer->setItems($itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateForCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $itemTransfers = $this->recalculateByShippingAddressAndItemTransfers($calculableObjectTransfer->getShippingAddress(), $calculableObjectTransfer->getItems(), $calculableObjectTransfer->getStore());
        $calculableObjectTransfer->setItems($itemTransfers);

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function recalculateByShippingAddressAndItemTransfers(
        ?AddressTransfer $shippingAddressTransfer,
        ArrayObject $itemTransfers,
        ?StoreTransfer $storeTransfer = null
    ): ArrayObject {
        $countryIso2Code = $this->getShippingCountryIsoCode($shippingAddressTransfer, $storeTransfer);
        $productOptionValueIds = $this->getAllProductOptionValueIds($itemTransfers);

        $taxRates = $this->findTaxRatesByIdOptionValueAndCountryIso2Code($productOptionValueIds, $countryIso2Code);

        return $this->setItemsTaxRate($itemTransfers, $taxRates);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    protected function getShippingCountryIsoCode(?AddressTransfer $shippingAddressTransfer, ?StoreTransfer $storeTransfer = null): string
    {
        if ($shippingAddressTransfer === null || !$shippingAddressTransfer->getIso2Code()) {
            if ($storeTransfer !== null) {
                $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
                $countries = $storeTransfer->getCountries();

                if ($countries) {
                    return reset($countries);
                }
            }

            return $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $shippingAddressTransfer->getIso2Code();
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int>
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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array $taxRates
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
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
     * @return array<int>
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
     * @param array<int> $productOptionValueIds
     * @param string $countryIso2Code
     *
     * @return array<\Orm\Zed\Shipment\Persistence\SpyShipmentMethod>
     */
    protected function findTaxRatesByIdOptionValueAndCountryIso2Code(array $productOptionValueIds, $countryIso2Code)
    {
        /** @var \Propel\Runtime\Collection\ArrayCollection $taxSetCollection */
        $taxSetCollection = $this->queryContainer
            ->queryTaxSetByIdProductOptionValueAndCountryIso2Code($productOptionValueIds, $countryIso2Code)
            ->find();

        return $taxSetCollection->toArray();
    }
}
