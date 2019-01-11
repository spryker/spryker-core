<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
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
        $countryIso2CodesByIdProductAbstracts = $this->getCountryIso2CodesByIdProductAbstracts($quoteTransfer->getItems());
        $idProductOptionValues = $this->getIdProductOptionValues($quoteTransfer->getItems());

        $taxRates = $this->findTaxRatesByIdOptionValuesAndCountryIso2Codes($idProductOptionValues, $countryIso2CodesByIdProductAbstracts);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->setProductOptionTaxRate(
                $itemTransfer,
                $taxRates,
                $countryIso2CodesByIdProductAbstracts[$itemTransfer->getIdProductAbstract()]
            );
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function getCountryIso2CodesByIdProductAbstracts(ArrayObject $itemTransfers): array
    {
        $countryIso2CodesByIdProductAbstracts = [];

        foreach ($itemTransfers as $itemTransfer) {
            $countryIso2CodesByIdProductAbstracts[$itemTransfer->getIdProductAbstract()] = $this->getShippingCountryIso2CodeByItem($itemTransfer);
        }

        return $countryIso2CodesByIdProductAbstracts;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
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
     * @return string
     */
    protected function getDefaultTaxCountryIso2Code(): string
    {
        if ($this->defaultTaxCountryIso2Code === null) {
            $this->defaultTaxCountryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $this->defaultTaxCountryIso2Code;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getShippingCountryIso2CodeByItem(ItemTransfer $itemTransfer): string
    {
        if ($this->hasItemShippingAddressDefaultTaxCountryIso2Code($itemTransfer)) {
            return $itemTransfer->getShipment()->getShippingAddress()->getIso2Code();
        }

        return $this->getDefaultTaxCountryIso2Code();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
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
                    $countryIso2Code
                )
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
     * @param int[] $idProductOptionValues
     * @param string[] $countryIso2CodesByIdProductAbstracts
     *
     * @todo fix return type
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod[]
     */
    protected function findTaxRatesByIdOptionValuesAndCountryIso2Codes(array $idProductOptionValues, array $countryIso2CodesByIdProductAbstracts): array
    {
        $groupedResults = [];
        $foundResults = $this->queryContainer
            ->queryTaxSetByIdProductOptionValueAndCountryIso2Codes(
                $idProductOptionValues,
                array_unique($countryIso2CodesByIdProductAbstracts)
            )
            ->find();

        foreach ($foundResults as $data) {
            $key = $this->getTaxGroupedKey(
                $data[ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_VALUE],
                $data[SpyCountryTableMap::COL_ISO2_CODE]
            );

            $groupedResults[$key] = $data[ProductOptionQueryContainer::COL_MAX_TAX_RATE];
        }

        return $groupedResults;
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

        return $shipmentTransfer !== null &&
            $shipmentTransfer->getShippingAddress() !== null &&
            $shipmentTransfer->getShippingAddress()->getIso2Code() !== null;
    }
}
