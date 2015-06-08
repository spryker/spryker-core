<?php

namespace SprykerFeature\Zed\ProductOptions\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method ProductOptionsDependencyContainer getDependencyContainer()
 */
class ProductOptionsFacade extends AbstractFacade
{

    /**
     * @param string $importKeyOptionType
     * @param null $importKeyTaxSet
     *
     * @return int
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importOptionType($importKeyOptionType, $importKeyTaxSet = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importOptionType($importKeyOptionType, $importKeyTaxSet);
    }

    /**
     * @param string $importKeyOptionValue
     * @param string $importKeyOptionType
     * @param float $price
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importOptionValue($importKeyOptionValue, $importKeyOptionType, $price = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importOptionValue($importKeyOptionValue, $importKeyOptionType, $price);
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionType
     * @param bool $isOptional
     * @param int $sequence
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionType($sku, $importKeyOptionType, $isOptional = false, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionType($sku, $importKeyOptionType, $isOptional, $sequence);
    }

    /**
     * @param int $idProductOptionType
     * @param string $importKeyOptionValue
     * @param int $sequence
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionValue($idProductOptionType, $importKeyOptionValue, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValue($idProductOptionType, $importKeyOptionValue, $sequence);
    }

    /**
     * @param string $sku
     * @param string $importKeyOptionTypeA
     * @param string $importKeyOptionTypeB
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionTypeExclusion($sku, $importKeyOptionTypeA, $importKeyOptionTypeB)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionTypeExclusion($sku, $importKeyOptionTypeA, $importKeyOptionTypeB);
    }

    /**
     * @param string $sku
     * @param int $idProductOptionValueSource
     * @param string $importKeyOptionValueTarget
     * @param string $operator
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importProductOptionValueConstraint($sku, $idProductOptionValueSource, $importKeyOptionValueTarget, $operator)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importProductOptionValueConstraint($sku, $idProductOptionValueSource, $importKeyOptionValueTarget, $operator);
    }

    /**
     * @param $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @return int
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null)
    {
        return $this->getDependencyContainer()->getDataImportWriterModel()->importPresetConfiguration($sku, $importKeysOptionValues, $isDefault, $sequence);
    }
}
