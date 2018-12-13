<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Spryker\Zed\Tax\Business\Model\CalculatorInterface;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

class ProductItemTaxRateCalculator implements CalculatorInterface
{
    /**
     * @var \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface
     */
    protected $taxQueryContainer;

    /**
     * @var \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface
     */
    protected $taxFacade;

    /**
     * @param \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface $taxQueryContainer
     * @param \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface $taxFacade
     */
    public function __construct(TaxProductConnectorQueryContainerInterface $taxQueryContainer, TaxProductConnectorToTaxInterface $taxFacade)
    {
        $this->taxQueryContainer = $taxQueryContainer;
        $this->taxFacade = $taxFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $allIdProductAbstracts = $countryIso2Codes = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $countryIso2Code = $this->getShippingCountryIso2CodeByItem($itemTransfer);
            $idProductAbstract = $itemTransfer->getIdProductAbstract();
            $allIdProductAbstracts[] = $idProductAbstract;
            $countryIso2Codes[$idProductAbstract] = $countryIso2Code;
        }

        $taxRates = $this->findTaxRatesByAllIdProductAbstractsAndCountryIso2Codes($allIdProductAbstracts, array_unique($countryIso2Codes));

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $taxRate = $this->getEffectiveTaxRate(
                $taxRates,
                $itemTransfer->getIdProductAbstract(),
                $allIdProductAbstracts[$itemTransfer->getIdProductAbstract()]
            );
            $itemTransfer->setTaxRate($taxRate);
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
     * @param array $taxRates
     * @param int $idProductAbstract
     * @param string $iso2Code
     *
     * @return float
     */
    protected function getEffectiveTaxRate(array $taxRates, int $idProductAbstract, string $iso2Code): float
    {
        $key = $iso2Code . $idProductAbstract;
        if (isset($taxRates[$key])) {
            return (float)$taxRates[$key];
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param int[] $allIdProductAbstractsAndIsoCodes
     * @param string[] $countryIso2Codes
     *
     * @return array
     */
    protected function findTaxRatesByAllIdProductAbstractsAndCountryIso2Codes(array $allIdProductAbstractsAndIsoCodes, array $countryIso2Codes): array
    {
        $groupedResults = [];
        $foundResults = $this->taxQueryContainer->queryTaxSetByIdProductAbstractAndCountryIso2Codes($allIdProductAbstractsAndIsoCodes, $countryIso2Codes)->find();
        foreach ($foundResults as $data) {
            $key = $data[SpyCountryTableMap::COL_ISO2_CODE] . $data[TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT];
            $groupedResults[$key] = $data[TaxProductConnectorQueryContainer::COL_MAX_TAX_RATE];
        }

        return $groupedResults;
    }
}
