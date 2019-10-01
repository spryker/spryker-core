<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Calculator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

/**
 * @deprecated Use \Spryker\Zed\ProductOption\Business\Calculator\ProductOptionTaxRateWithItemShipmentTaxRateCalculator instead.
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
        $countryIso2Code = $this->getShippingCountryIsoCode($quoteTransfer);
        $productOptionValueIds = $this->getAllProductOptionValueIds($quoteTransfer);

        $taxRates = $this->findTaxRatesByIdOptionValueAndCountryIso2Code($productOptionValueIds, $countryIso2Code);

        $this->setItemsTaxRate($quoteTransfer, $taxRates);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getShippingCountryIsoCode(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShippingAddress() === null || !$quoteTransfer->getShippingAddress()->getIso2Code()) {
            return $this->taxFacade->getDefaultTaxCountryIso2Code();
        }

        return $quoteTransfer->getShippingAddress()->getIso2Code();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAllProductOptionValueIds(QuoteTransfer $quoteTransfer)
    {
        $productOptionValueIds = [];
        foreach ($quoteTransfer->getItems() as $item) {
            $productOptionValueIds = array_merge($productOptionValueIds, $this->getProductOptionValueIds($item));
        }

        return $productOptionValueIds;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $taxRates
     *
     * @return void
     */
    protected function setItemsTaxRate(QuoteTransfer $quoteTransfer, array $taxRates)
    {
        foreach ($quoteTransfer->getItems() as $item) {
            $this->setProductOptionTaxRate($item, $taxRates);
        }
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
     * @return array
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
