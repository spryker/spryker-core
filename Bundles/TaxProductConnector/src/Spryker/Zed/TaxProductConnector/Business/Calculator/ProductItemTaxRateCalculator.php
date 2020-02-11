<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainer;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface;

class ProductItemTaxRateCalculator implements CalculatorInterface
{
    protected const TAX_EXEMPT_PLACEHOLDER = 'Tax Exempt';
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
     * @var float
     */
    protected $defaultTaxRate;

    /**
     * @param \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface $taxQueryContainer
     * @param \Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToTaxInterface $taxFacade
     */
    public function __construct(
        TaxProductConnectorQueryContainerInterface $taxQueryContainer,
        TaxProductConnectorToTaxInterface $taxFacade
    ) {
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
        $foundResults = $this->taxQueryContainer
            ->queryTaxSetByIdProductAbstractAndCountryIso2Codes(
                $this->getIdProductAbstruct($quoteTransfer->getItems()),
                $this->getCountryIso2Codes($quoteTransfer->getItems())
            )
            ->find();

        $taxRatesByIdProductAbstractAndCountry = $this->mapByIdProductAbstractAndCountry($foundResults);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $taxRate = $this->getEffectiveTaxRate(
                $taxRatesByIdProductAbstractAndCountry,
                $itemTransfer->getIdProductAbstract(),
                $this->getShippingCountryIso2CodeByItem($itemTransfer)
            );
            $itemTransfer->setTaxRate($taxRate);
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function getCountryIso2Codes(iterable $itemTransfers): array
    {
        $result = [];
        foreach ($itemTransfers as $itemTransfer) {
            $result[] = $this->getShippingCountryIso2CodeByItem($itemTransfer);
        }

        return array_unique($result);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function getIdProductAbstruct(iterable $itemTransfers): array
    {
        $result = [];
        foreach ($itemTransfers as $itemTransfer) {
            $result[] = $itemTransfer->getIdProductAbstract();
        }

        return array_unique($result);
    }

    /**
     * @param iterable|\Propel\Runtime\Collection\ArrayCollection $taxRatesByCountryAndProduct
     *
     * @return array
     */
    protected function mapByIdProductAbstractAndCountry(iterable $taxRatesByCountryAndProduct): array
    {
        $mappedResult = [];
        foreach ($taxRatesByCountryAndProduct as $taxRate) {
            $idProductAbstract = $taxRate[TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT];
            $iso2Code = $taxRate[TaxProductConnectorQueryContainer::COL_COUNTRY_CODE] ?? static::TAX_EXEMPT_PLACEHOLDER;
            $maxTaxRate = $taxRate[TaxProductConnectorQueryContainer::COL_MAX_TAX_RATE];

            $mappedResult[$idProductAbstract][$iso2Code] = $maxTaxRate;
        }

        return $mappedResult;
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
     * @return float
     */
    protected function getDefaultTaxRate(): float
    {
        if ($this->defaultTaxRate === null) {
            $this->defaultTaxRate = $this->taxFacade->getDefaultTaxRate();
        }

        return $this->defaultTaxRate;
    }

    /**
     * @param array $mappedTaxRates
     * @param int $idProductAbstract
     * @param string $countryIso2Code
     *
     * @return float
     */
    protected function getEffectiveTaxRate(
        array $mappedTaxRates,
        int $idProductAbstract,
        string $countryIso2Code
    ): float {
        $taxRate = $mappedTaxRates[$idProductAbstract][$countryIso2Code] ??
            $mappedTaxRates[$idProductAbstract][static::TAX_EXEMPT_PLACEHOLDER] ??
            $this->getDefaultTaxRate();

        return (float)$taxRate;
    }

    /**
     * @param array $resultEntry
     *
     * @return string
     */
    protected function createKeyForMappedArray(array $resultEntry): string
    {
        return $resultEntry[TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT] . '_' . $resultEntry[TaxProductConnectorQueryContainer::COL_COUNTRY_CODE];
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
