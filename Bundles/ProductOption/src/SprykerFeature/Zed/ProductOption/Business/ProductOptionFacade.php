<?php

namespace SprykerFeature\Zed\ProductOption\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

use SprykerFeature\Zed\ProductOption\Business\Exception\MissingOptionTypeException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingOptionValueException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException;

/**
 * @method ProductOptionDependencyContainer getDependencyContainer()
 */
class ProductOptionFacade extends AbstractFacade
{

    /**
     * @param string $importKeyOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     *
     * @return int
     */
    public function importProductOptionType($importKeyOptionType, array $localizedNames = [], $importKeyTaxSet = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionType($importKeyOptionType, $localizedNames, $importKeyTaxSet);
    }

    /**
     * @param string $importKeyOptionValue
     * @param string $importKeyOptionType
     * @param array $localizedNames
     * @param float $price
     *
     * @return int
     *
     * @throws MissingOptionTypeException
     */
    public function importProductOptionValue($importKeyOptionValue, $importKeyOptionType, array $localizedNames = [], $price = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValue($importKeyOptionValue, $importKeyOptionType, $localizedNames, $price);
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingOptionTypeException
     */
    public function importProductOptionTypeUsage($sku, $importKeyOptionType, $isOptional = false, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionTypeUsage($sku, $importKeyOptionType, $isOptional, $sequence);
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param string $importKeyOptionValue
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionTypeUsageException
     * @throws MissingOptionValueException
     */
    public function importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyOptionValue, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyOptionValue, $sequence);
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionTypeA
     * @param string $importKeyOptionTypeB
     *
     * @throws MissingOptionTypeException
     * @throw MissingProductOptionTypeUsageException
     */
    public function importProductOptionTypeUsageExclusion($sku, $importKeyOptionTypeA, $importKeyOptionTypeB)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionTypeUsageExclusion($sku, $importKeyOptionTypeA, $importKeyOptionTypeB);
    }

    /**
     * @param string $sku
     * @param int $idProductOptionValueUsageSource
     * @param string $importKeyOptionValueTarget
     * @param string $operator
     *
     * @throws MissingProductOptionValueUsageException
     * @throws MissingOptionValueException
     * @throws MissingProductOptionValueUsageException
     */
    public function importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyOptionValueTarget, $operator)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyOptionValueTarget, $operator);
    }

    /**
     * @param $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionValueUsageException
     * @throws MissingOptionValueException
     * @throws MissingProductOptionValueUsageException
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importPresetConfiguration($sku, $importKeysOptionValues, $isDefault, $sequence);
    }
}
