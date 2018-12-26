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
     * @var string
     */
    protected $defaultTaxCountryIso2Code;

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
        $countryIso2CodesByIdProductAbstracts = $this->getCountryIso2CodesByIdProductAbstracts($quoteTransfer->getItems());

        $taxRates = $this->findTaxRatesByAllIdProductAbstractsAndCountryIso2Codes($countryIso2CodesByIdProductAbstracts);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $taxRate = $this->getEffectiveTaxRate(
                $taxRates,
                $itemTransfer->getIdProductAbstract(),
                $countryIso2CodesByIdProductAbstracts[$itemTransfer->getIdProductAbstract()]
            );
            $itemTransfer->setTaxRate($taxRate);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function getCountryIso2CodesByIdProductAbstracts(array $itemTransfers): array
    {
        $countryIso2CodesByIdProductAbstracts = [];

        foreach ($itemTransfers as $itemTransfer) {
            $countryIso2CodesByIdProductAbstracts[$itemTransfer->getIdProductAbstract()] = $this->getShippingCountryIso2CodeByItem($itemTransfer);
        }

        return $countryIso2CodesByIdProductAbstracts;
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
        if (
            ($itemTransfer->getShipment() === null)
            || ($itemTransfer->getShipment()->getShippingAddress() === null)
        ) {
            return $this->getDefaultTaxCountryIso2Code();
        }

        $isoCode = $itemTransfer->getShipment()->getShippingAddress()->getIso2Code();

        return $isoCode ?? $this->getDefaultTaxCountryIso2Code();
    }

    /**
     * @param array $taxRates
     * @param int $idProductAbstract
     * @param string $countryIso2Code
     *
     * @return float
     */
    protected function getEffectiveTaxRate(array $taxRates, int $idProductAbstract, string $countryIso2Code): float
    {
        $key = $this->getTaxGroupedKey($idProductAbstract, $countryIso2Code);

        if (isset($taxRates[$key])) {
            return (float)$taxRates[$key];
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param string[] $countryIso2CodesByIdProductAbstracts
     *
     * @return array
     */
    protected function findTaxRatesByAllIdProductAbstractsAndCountryIso2Codes(array $countryIso2CodesByIdProductAbstracts): array
    {
        $groupedResults = [];
        $foundResults = $this->taxQueryContainer
            ->queryTaxSetByIdProductAbstractAndCountryIso2Codes(
                $this->getIdProductAbstracts($countryIso2CodesByIdProductAbstracts),
                $this->getUniqueCountryIso2Codes($countryIso2CodesByIdProductAbstracts)
            )
            ->find();

        foreach ($foundResults as $data) {
            $key = $this->getTaxGroupedKey($data[TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT], $data[SpyCountryTableMap::COL_ISO2_CODE]);
            $groupedResults[$key] = $data[TaxProductConnectorQueryContainer::COL_MAX_TAX_RATE];
        }

        return $groupedResults;
    }


    /**
     * @param string[] $countryIso2CodesByIdProductAbstracts
     *
     * @return int[]
     */
    protected function getIdProductAbstracts(array $countryIso2CodesByIdProductAbstracts): array
    {
        return array_keys($countryIso2CodesByIdProductAbstracts);
    }

    /**
     * @param string[] $countryIso2Codes
     *
     * @return string[]
     */
    protected function getUniqueCountryIso2Codes(array $countryIso2Codes): array
    {
        return array_unique($countryIso2Codes);
    }

    /**
     * @param int $idProductAbstract
     * @param string $countryIso2Code
     *
     * @return string
     */
    protected function getTaxGroupedKey(int $idProductAbstract, string $countryIso2Code): string
    {
        return $countryIso2Code . $idProductAbstract;
    }
}
