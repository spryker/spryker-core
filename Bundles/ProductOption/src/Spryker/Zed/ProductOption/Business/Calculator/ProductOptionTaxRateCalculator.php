<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;

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
        $productOptionValueIds = $countryIso2Codes = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $countryIso2Code = $this->getShippingCountryIso2CodeByItem($itemTransfer);
            $productOptionValueIds[$countryIso2Code] = array_merge($productOptionValueIds[$countryIso2Code], $this->getProductOptionValueIds($itemTransfer));
            $countryIso2Codes[$itemTransfer->getIdProductAbstract()] = $countryIso2Code;
        }

        $taxRates = $this->findTaxRatesByIdOptionValuesAndCountryIso2Codes($productOptionValueIds, array_unique($countryIso2Codes));

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->setProductOptionTaxRate($itemTransfer, $taxRates, $countryIso2Codes[$itemTransfer->getIdProductAbstract()]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getShippingCountryIso2CodeByItem(ItemTransfer $itemTransfer): string
    {
        if (
            ($itemTransfer->getShipment() === null)
            || ($itemTransfer->getShipment()->getShippingAddress() === null)
        ) {
            return $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        $isoCode = $itemTransfer->getShipment()->getShippingAddress()->getIso2Code();

        return $isoCode ?: $this->taxFacade->getDefaultTaxCountryIso2Code();
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
            $productOptionTransfer->setTaxRate($this->getEffectiveTaxRate($taxRates, $productOptionTransfer->getIdProductOptionValue(), $countryIso2Code));
        }
    }

    /**
     * @param array $taxRates
     * @param int $idOptionValue
     * @param string $iso2Code
     *
     * @return float
     */
    protected function getEffectiveTaxRate(array $taxRates, int $idOptionValue, string $iso2Code): float
    {
        $key = $iso2Code . $idOptionValue;
        if (isset($taxRates[$key])) {
            return (float)$taxRates[$key];
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param int[] $productOptionValueIds
     * @param string[] $countryIso2Codes
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethod[]
     */
    protected function findTaxRatesByIdOptionValuesAndCountryIso2Codes(array $productOptionValueIds, array $countryIso2Codes): array
    {
        $groupedResults = [];
        $foundResults = $this->queryContainer->queryTaxSetByIdProductOptionValueAndCountryIso2Codes($productOptionValueIds, $countryIso2Codes)->find();
        foreach ($foundResults as $data) {
            $key = $data[SpyCountryTableMap::COL_ISO2_CODE] . $data[ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_VALUE];
            $groupedResults[$key] = $data[ProductOptionQueryContainer::COL_MAX_TAX_RATE];
        }

        return $groupedResults;
    }
}
