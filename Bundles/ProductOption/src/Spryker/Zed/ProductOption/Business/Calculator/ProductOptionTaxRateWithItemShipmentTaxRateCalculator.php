<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToStoreFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionTaxRateWithItemShipmentTaxRateCalculator implements CalculatorInterface
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
     * @var string
     */
    protected $defaultTaxCountryIso2Code;

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
        $this->recalculateForItemTransfers($quoteTransfer->getItems(), $quoteTransfer->getStore());
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateForCalculableObject(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $itemTransfers = $this->recalculateForItemTransfers($calculableObjectTransfer->getItems(), $calculableObjectTransfer->getStore());
        $calculableObjectTransfer->setItems($itemTransfers);

        return $calculableObjectTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function recalculateForItemTransfers(ArrayObject $itemTransfers, ?StoreTransfer $storeTransfer = null): ArrayObject
    {
        $countryIso2Codes = $this->getCountryIso2Codes($itemTransfers, $storeTransfer);
        $idProductOptionValues = $this->getIdProductOptionValues($itemTransfers);

        $taxRates = $this->findTaxRatesByIdOptionValuesAndCountryIso2Codes($idProductOptionValues, $countryIso2Codes);

        foreach ($itemTransfers as $itemTransfer) {
            $this->setProductOptionTaxRate(
                $itemTransfer,
                $taxRates,
                $this->getShippingCountryIso2CodeByItem($itemTransfer, $storeTransfer),
            );
        }

        return $itemTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return array<string>
     */
    protected function getCountryIso2Codes(ArrayObject $itemTransfers, ?StoreTransfer $storeTransfer = null): array
    {
        $countryIso2CodesByIdProductAbstracts = [];

        foreach ($itemTransfers as $itemTransfer) {
            $countryIso2CodesByIdProductAbstracts[] = $this->getShippingCountryIso2CodeByItem($itemTransfer, $storeTransfer);
        }

        return $countryIso2CodesByIdProductAbstracts;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int>
     */
    protected function getIdProductOptionValues(ArrayObject $itemTransfers): array
    {
        $idProductOptionValues = [];

        foreach ($itemTransfers as $itemTransfer) {
            $idProductOptionValues = array_merge($idProductOptionValues, $this->getProductOptionValueIds($itemTransfer));
        }

        return array_unique($idProductOptionValues);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    protected function getDefaultTaxCountryIso2Code(?StoreTransfer $storeTransfer = null): string
    {
        if ($this->defaultTaxCountryIso2Code === null) {
            if ($storeTransfer !== null) {
                $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
                $countries = $storeTransfer->getCountries();

                if ($countries) {
                    $this->defaultTaxCountryIso2Code = reset($countries);

                    return $this->defaultTaxCountryIso2Code;
                }
            }
            $this->defaultTaxCountryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $this->defaultTaxCountryIso2Code;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    protected function getShippingCountryIso2CodeByItem(ItemTransfer $itemTransfer, ?StoreTransfer $storeTransfer = null): string
    {
        if ($this->hasItemShippingAddressDefaultTaxCountryIso2Code($itemTransfer)) {
            return $itemTransfer->getShipment()->getShippingAddress()->getIso2Code();
        }

        return $this->getDefaultTaxCountryIso2Code($storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<int>
     */
    protected function getProductOptionValueIds(ItemTransfer $itemTransfer): array
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
     * @param string $countryIso2Code
     *
     * @return void
     */
    protected function setProductOptionTaxRate(ItemTransfer $itemTransfer, array $taxRates, string $countryIso2Code): void
    {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionTransfer->setTaxRate(
                $this->getEffectiveTaxRate(
                    $taxRates,
                    $productOptionTransfer->getIdProductOptionValue(),
                    $countryIso2Code,
                ),
            );
        }
    }

    /**
     * @param array $taxRates
     * @param int $idOptionValue
     * @param string $countryIso2Code
     *
     * @return float
     */
    protected function getEffectiveTaxRate(array $taxRates, int $idOptionValue, string $countryIso2Code): float
    {
        $key = $this->getTaxGroupedKey($idOptionValue, $countryIso2Code);

        if (isset($taxRates[$key])) {
            return (float)$taxRates[$key];
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param array<int> $idProductOptionValues
     * @param array<string> $countryIso2Codes
     *
     * @return array
     */
    protected function findTaxRatesByIdOptionValuesAndCountryIso2Codes(array $idProductOptionValues, array $countryIso2Codes): array
    {
        $taxSetCollection = $this->queryContainer
            ->queryTaxSetByIdProductOptionValueAndCountryIso2Codes(
                $idProductOptionValues,
                array_unique($countryIso2Codes),
            )
            ->find();

        return $this->getGroupedTaxSetCollection($taxSetCollection);
    }

    /**
     * @param iterable<\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue> $taxSetCollection
     *
     * @return array
     */
    protected function getGroupedTaxSetCollection(iterable $taxSetCollection): array
    {
        $groupedTaxSetCollection = [];

        foreach ($taxSetCollection as $data) {
            $key = $this->getTaxGroupedKey(
                $data[ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_VALUE],
                $data[ProductOptionQueryContainer::COL_COUNTRY_ISO2_CODE],
            );

            $groupedTaxSetCollection[$key] = $data[ProductOptionQueryContainer::COL_MAX_TAX_RATE];
        }

        return $groupedTaxSetCollection;
    }

    /**
     * @param int $idOptionValue
     * @param string $countryIso2Code
     *
     * @return string
     */
    protected function getTaxGroupedKey(int $idOptionValue, string $countryIso2Code): string
    {
        return $countryIso2Code . $idOptionValue;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasItemShippingAddressDefaultTaxCountryIso2Code(ItemTransfer $itemTransfer): bool
    {
        $shipmentTransfer = $itemTransfer->getShipment();

        return $shipmentTransfer !== null
            && $shipmentTransfer->getShippingAddress() !== null
            && $shipmentTransfer->getShippingAddress()->getIso2Code() !== null;
    }
}
