<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionValueException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException;

/**
 * @method ProductOptionDependencyContainer getDependencyContainer()
 */
class ProductOptionFacade extends AbstractFacade
{

    /**
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     *
     * @return int
     */
    public function importProductOptionType($importKeyProductOptionType, array $localizedNames = [], $importKeyTaxSet = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionType($importKeyProductOptionType, $localizedNames, $importKeyTaxSet);
    }

    /**
     * @param string $importKeyProductOptionValue
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param float $price
     *
     * @throws MissingProductOptionTypeException
     *
     * @return int
     */
    public function importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, array $localizedNames = [], $price = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, $localizedNames, $price);
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @throws MissingProductOptionTypeException
     *
     * @return int
     */
    public function importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional = false, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional, $sequence);
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param string $importKeyProductOptionValue
     * @param int $sequence
     *
     * @throws MissingProductOptionTypeUsageException
     * @throws MissingProductOptionValueException
     *
     * @return int
     */
    public function importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence);
    }

    /**
     * @param string $sku
     * @param string $importKeyProductOptionTypeA
     * @param string $importKeyProductOptionTypeB
     *
     * @throws MissingProductOptionTypeException
     * @throws MissingProductOptionTypeUsageException
     */
    public function importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB);
    }

    /**
     * @param string $sku
     * @param int $idProductOptionValueUsageSource
     * @param string $importKeyProductOptionValueTarget
     * @param string $operator
     *
     * @throws MissingProductOptionValueUsageException
     * @throws MissingProductOptionValueException
     * @throws MissingProductOptionValueUsageException
     */
    public function importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator);
    }

    /**
     * @param string $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @throws MissingProductOptionValueUsageException
     * @throws MissingProductOptionValueException
     * @throws MissingProductOptionValueUsageException
     *
     * @return int
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importPresetConfiguration($sku, $importKeysOptionValues, $isDefault, $sequence);
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForConcreteProduct($idProduct, $idLocale)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getTypeUsagesForConcreteProduct($idProduct, $idLocale);
    }

    /**
     * @param int $idTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idTypeUsage, $idLocale)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getValueUsagesForTypeUsage($idTypeUsage, $idLocale);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idTypeUsage)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getTypeExclusionsForTypeUsage($idTypeUsage);
    }

    /**
     * @param int $idValueUsage
     *
     * @return array
     */
    public function getValueConstraintsForValueUsage($idValueUsage)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getValueConstraintsForValueUsage($idValueUsage);
    }

    /**
     * @param int $idValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function getValueConstraintsForValueUsageByOperator($idValueUsage, $operator)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getValueConstraintsForValueUsageByOperator($idValueUsage, $operator);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function getConfigPresetsForConcreteProduct($idProduct)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getConfigPresetsForConcreteProduct($idProduct);
    }

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getValueUsagesForConfigPreset($idConfigPreset);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idTypeUsage)
    {
        return $this->getDependencyContainer()->getProductOptionReaderModel()->getEffectiveTaxRateForTypeUsage($idTypeUsage);
    }


    public function flushBuffer()
    {
        $this->getDependencyContainer()->getDataImportWriterModel()->flushBuffer();
    }

}
