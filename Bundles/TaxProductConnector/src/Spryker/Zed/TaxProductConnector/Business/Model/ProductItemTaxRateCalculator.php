<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
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

    protected function useNewShipment()
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        if ($this->useNewShipment()) {
            $this->newRecalculate($quoteTransfer);
            return;
        }

        $countryIso2Code = $this->getShippingCountryIso2Code($quoteTransfer);
        $allIdProductAbstracts = $this->getAllIdAbstractProducts($quoteTransfer);

        if (!$countryIso2Code) {
            $countryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        $taxRates = $this->findTaxRatesByAllIdProductAbstractsAndCountryIso2Code($allIdProductAbstracts, $countryIso2Code);
        $this->setItemsTax($quoteTransfer, $taxRates);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function newRecalculate(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $countryIso2Code = $this->newGetShippingCountryIso2Code($itemTransfer);
            $idProductAbstracts = $itemTransfer->getIdProductAbstract();
//@todo: group product IDs with ISO codes
            if (!$countryIso2Code) {
                $countryIso2Code = $this->taxFacade->getDefaultTaxCountryIso2Code();
            }

            $taxRates = $this->findTaxRatesByAllIdProductAbstractsAndCountryIso2Code($allIdProductAbstracts, $countryIso2Code);
            $this->setItemsTax($quoteTransfer, $taxRates);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getShippingCountryIso2Code(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShippingAddress() === null) {
            return $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $quoteTransfer->getShippingAddress()->getIso2Code();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function newGetShippingCountryIso2Code(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getShipment() === null) {
            return $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $itemTransfer->getShipment()->getIso2Code();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAllIdAbstractProducts(QuoteTransfer $quoteTransfer)
    {
        $allIdProductAbstracts = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $allIdProductAbstracts[] = $itemTransfer->getIdProductAbstract();
        }

        return $allIdProductAbstracts;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $taxRates
     *
     * @return void
     */
    protected function setItemsTax(QuoteTransfer $quoteTransfer, array $taxRates)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setTaxRate($this->getEffectiveTaxRate($taxRates, $itemTransfer->getIdProductAbstract()));
        }
    }

    /**
     * @param array $taxRates
     * @param int $idProductAbstract
     *
     * @return float
     */
    protected function getEffectiveTaxRate(array $taxRates, $idProductAbstract)
    {
        foreach ($taxRates as $taxRate) {
            if ((int)$taxRate[TaxProductConnectorQueryContainer::COL_ID_ABSTRACT_PRODUCT] === (int)$idProductAbstract) {
                return (float)$taxRate[TaxProductConnectorQueryContainer::COL_MAX_TAX_RATE];
            }
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param array $allIdProductAbstracts
     * @param string $countryIso2Code
     *
     * @return array
     */
    protected function findTaxRatesByAllIdProductAbstractsAndCountryIso2Code(array $allIdProductAbstracts, $countryIso2Code)
    {
        return $this->taxQueryContainer->queryTaxSetByIdProductAbstractAndCountryIso2Code($allIdProductAbstracts, $countryIso2Code)
            ->find()
            ->toArray();
    }
}
