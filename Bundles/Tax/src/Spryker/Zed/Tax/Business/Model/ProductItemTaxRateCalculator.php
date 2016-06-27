<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Tax\Persistence\TaxQueryContainer;
use Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface;

class ProductItemTaxRateCalculator implements CalculatorInterface
{

    /**
     * @var \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface
     */
    protected $taxQueryContainer;

    /**
     * @var \Spryker\Zed\Tax\Business\Model\TaxDefaultInterface
     */
    protected $taxDefault;

    /**
     * @param \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface $taxQueryContainer
     * @param \Spryker\Zed\Tax\Business\Model\TaxDefaultInterface $taxDefault
     */
    public function __construct(TaxQueryContainerInterface $taxQueryContainer, TaxDefaultInterface $taxDefault)
    {
        $this->taxQueryContainer = $taxQueryContainer;
        $this->taxDefault = $taxDefault;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $country = $this->getShippingCountryIsoCode($quoteTransfer);
        $allIdProductAbstracts = $this->getAllIdAbstractProducts($quoteTransfer);

        $taxRates = $this->findTaxRatesByAllIdProductAbstractsAndCountry($allIdProductAbstracts, $country);

        $this->setItemsTax($quoteTransfer, $taxRates);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getShippingCountryIsoCode(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShippingAddress() === null) {
            return $this->taxDefault->getDefaultCountry();
        }

        return $quoteTransfer->getShippingAddress()->getIso2Code();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAllIdAbstractProducts(QuoteTransfer $quoteTransfer)
    {
        $allIdProductAbstracts = [];
        foreach ($quoteTransfer->getItems() as $item) {
            $allIdProductAbstracts[] = $item->getIdProductAbstract();
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
        foreach ($quoteTransfer->getItems() as $item) {
            $item->setTaxRate($this->getEffectiveTaxRate($taxRates, $item->getIdProductAbstract()));
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
            if ($taxRate[TaxQueryContainer::COL_ID_ABSTRACT_PRODUCT] === $idProductAbstract) {
                return (float)$taxRate[TaxQueryContainer::COL_SUM_TAX_RATE];
            }
        }

        return $this->taxDefault->getDefaultTaxRate();
    }

    /**
     * @param array $allIdProductAbstracts
     * @param int $country
     *
     * @return array
     */
    protected function findTaxRatesByAllIdProductAbstractsAndCountry(array $allIdProductAbstracts, $country)
    {
        return $this->taxQueryContainer->queryTaxSetByIdProductAbstractAndCountry($allIdProductAbstracts, $country)
            ->find()
            ->toArray();
    }

}
