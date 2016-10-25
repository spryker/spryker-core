<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getFactory()
 */
class ProductOptionFacade extends AbstractFacade implements ProductOptionFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductOptionValueUsage
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $localeCode)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getProductOption($idProductOptionValueUsage, $localeCode);
    }

    /**
     * @api
     *
     * @param int $idProduct
     * @param string $localeCode
     *
     * @return mixed
     */
    public function getProductOptionsByIdProduct($idProduct, $localeCode)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getProductOptionsByIdProductAndIdLocale($idProduct, $localeCode);
    }

    /**
     * @api
     *
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param string|null $importKeyTaxSet
     *
     * @return int
     */
    public function importProductOptionType($importKeyProductOptionType, array $localizedNames = [], $importKeyTaxSet = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionType($importKeyProductOptionType, $localizedNames, $importKeyTaxSet);
    }

    /**
     * @api
     *
     * @param string $importKeyProductOptionValue
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param float|null $price
     *
     * @return int
     */
    public function importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, array $localizedNames = [], $price = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, $localizedNames, $price);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $importKeyProductOptionType
     * @param bool $isOptional
     * @param int|null $sequence
     *
     * @return int
     */
    public function importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional = false, $sequence = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional, $sequence);
    }

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     * @param string $importKeyProductOptionValue
     * @param int|null $sequence
     *
     * @return int
     */
    public function importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $importKeyProductOptionTypeA
     * @param string $importKeyProductOptionTypeB
     *
     * @return void
     */
    public function importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB)
    {
        $this->getFactory()->createDataImportWriterModel()->importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param int $idProductOptionValueUsageSource
     * @param string $importKeyProductOptionValueTarget
     * @param string $operator
     *
     * @return void
     */
    public function importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator)
    {
        $this->getFactory()->createDataImportWriterModel()->importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int|null $sequence
     *
     * @return int
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importPresetConfiguration($sku, $importKeysOptionValues, $isDefault, $sequence);
    }

    /**
     * @api
     *
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForProductConcrete($idProduct, $idLocale)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getTypeUsagesForProductConcrete($idProduct, $idLocale);
    }

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale);
    }

    /**
     * @api
     *
     * @param int $idProductAttributeTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idProductAttributeTypeUsage)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getTypeExclusionsForTypeUsage($idProductAttributeTypeUsage);
    }

    /**
     * @api
     *
     * @param int $idValueUsage
     *
     * @return array
     */
    public function getValueConstraintsForValueUsage($idValueUsage)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getValueConstraintsForValueUsage($idValueUsage);
    }

    /**
     * @api
     *
     * @param int $idValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function getValueConstraintsForValueUsageByOperator($idValueUsage, $operator)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getValueConstraintsForValueUsageByOperator($idValueUsage, $operator);
    }

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getConfigPresetsForProductConcrete($idProduct)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getConfigPresetsForProductConcrete($idProduct);
    }

    /**
     * @api
     *
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getValueUsagesForConfigPreset($idConfigPreset);
    }

    /**
     * @api
     *
     * @param int $idProductAttributeTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage);
    }

    /**
     * @api
     *
     * @return void
     */
    public function flushBuffer()
    {
        $this->getFactory()->createDataImportWriterModel()->flushBuffer();
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSaleOrderProductOptions(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createProductOptionOrderSaver()->save($quoteTransfer, $checkoutResponse);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderItemProductOptionGrossPrice(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createItemProductOptionGrossPriceAggregator()->aggregate($orderTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregateOrderSubtotalWithProductOptions(OrderTransfer $orderTransfer)
    {
        $this->getFactory()->createSubtotalWithProductOption()->aggregate($orderTransfer);
    }

    /**
     * Specification:
     *  - Calculate tax rate for current quote
     *  - Set tax rate perecentage
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductOptionTaxRate(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()->createProductOptionTaxRateCalculator()->recalculate($quoteTransfer);
    }

}
