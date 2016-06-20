<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTaxBridgeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionTaxRateCalculator implements CalculatorInterface
{

    /**
     * @var ProductOptionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var ProductOptionToTaxBridgeInterface
     */
    protected $taxFacade;

    /**
     * @var array
     */
    protected $taxRates;

    /**
     * @param ProductOptionQueryContainerInterface $queryContainer
     * @param ProductOptionToTaxBridgeInterface $taxFacade
     */
    public function __construct(ProductOptionQueryContainerInterface $queryContainer, ProductOptionToTaxBridgeInterface $taxFacade)
    {
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
        $country = $this->getShippingCountryIsoCode($quoteTransfer);
        $idsProductOption = $this->getIdsProductOption($quoteTransfer);

        $this->taxRates = $this->queryContainer
            ->queryTaxSetByProductOptionTypeUsageAndCountry(
                $idsProductOption,
                $country
            )->find();

        $this->setItemsTax($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getShippingCountryIsoCode(QuoteTransfer $quoteTransfer)
    {
        if ($quoteTransfer->getShippingAddress() === null) {
            return $this->taxFacade->getDefaultTaxCountry();
        }

        return $quoteTransfer->getShippingAddress()->getIso2Code();
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getIdsProductOption(QuoteTransfer $quoteTransfer)
    {
        $idsProductOption = [];
        foreach ($quoteTransfer->getItems() as $item) {
            $idsProductOption = array_merge($idsProductOption, $this->getIdsProductOptionPerItem($item));
        }

        return $idsProductOption;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function setItemsTax(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $item) {
            $this->setProductOptionTaxRate($item);
        }
    }

    /**
     * @param $idProductOptionTypeUsage
     *
     * @return float
     */
    protected function getEffectiveTaxRate($idProductOptionTypeUsage)
    {
        foreach ($this->taxRates as $taxRate) {
            if ($taxRate[ProductOptionQueryContainer::COL_ID_PRODUCT_OPTION_TYPE_USAGE] === $idProductOptionTypeUsage) {
                return (float) $taxRate[ProductOptionQueryContainer::COL_SUM_TAX_RATE];
            }
        }

        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @param ItemTransfer $item
     *
     * @return array
     */
    protected function getIdsProductOptionPerItem(ItemTransfer $item)
    {
        $idsProductOptionPerItem = [];
        foreach ($item->getProductOptions() as $productOption) {
            $idsProductOptionPerItem[] = $productOption->getIdOptionValueUsage();
        }

        return $idsProductOptionPerItem;
    }

    /**
     * @param ItemTransfer $item
     *
     * @return void
     */
    protected function setProductOptionTaxRate(ItemTransfer $item)
    {
        foreach ($item->getProductOptions() as $productOption) {
            $productOption->setTaxRate($this->getEffectiveTaxRate($productOption->getIdOptionValueUsage()));
        }
    }
}
