<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOption\Business\ProductOptionBusinessFactory getFactory()
 */
class ProductOptionFacade extends AbstractFacade implements ProductOptionFacadeInterface
{

    /**
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
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     *
     * @return int
     */
    public function importProductOptionType($importKeyProductOptionType, array $localizedNames = [], $importKeyTaxSet = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionType($importKeyProductOptionType, $localizedNames, $importKeyTaxSet);
    }

    /**
     * @param string $importKeyProductOptionValue
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param float $price
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException
     *
     * @return int
     */
    public function importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, array $localizedNames = [], $price = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, $localizedNames, $price);
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException
     *
     * @return int
     */
    public function importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional = false, $sequence = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional, $sequence);
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param string $importKeyProductOptionValue
     * @param int $sequence
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueException
     *
     * @return int
     */
    public function importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence);
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionTypeA
     * @param string $importKeyProductOptionTypeB
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException
     */
    public function importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB);
    }

    /**
     * @param string $sku
     * @param int $idProductOptionValueUsageSource
     * @param string $importKeyProductOptionValueTarget
     * @param string $operator
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     */
    public function importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator)
    {
        return $this->getFactory()->createDataImportWriterModel()->importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator);
    }

    /**
     * @param string $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     *
     * @return int
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null)
    {
        return $this->getFactory()->createDataImportWriterModel()->importPresetConfiguration($sku, $importKeysOptionValues, $isDefault, $sequence);
    }

    /**
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
     * @param int $idProductAttributeTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idProductAttributeTypeUsage)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getTypeExclusionsForTypeUsage($idProductAttributeTypeUsage);
    }

    /**
     * @param int $idValueUsage
     *
     * @return array
     */
    public function getValueConstraintsForValueUsage($idValueUsage)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getValueConstraintsForValueUsage($idValueUsage);
    }

    /**
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
     * @param int $idProduct
     *
     * @return array
     */
    public function getConfigPresetsForProductConcrete($idProduct)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getConfigPresetsForProductConcrete($idProduct);
    }

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getValueUsagesForConfigPreset($idConfigPreset);
    }

    /**
     * @param int $idProductAttributeTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage)
    {
        return $this->getFactory()->createProductOptionReaderModel()->getEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage);
    }

    /**
     * @return void
     */
    public function flushBuffer()
    {
        $this->getFactory()->createDataImportWriterModel()->flushBuffer();
    }

}
