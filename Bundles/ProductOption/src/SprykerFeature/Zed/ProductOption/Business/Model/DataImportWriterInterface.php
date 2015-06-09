<?php

namespace SprykerFeature\Zed\ProductOption\Business\Model;

use SprykerFeature\Zed\ProductOption\Business\Exception\MissingOptionTypeException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingOptionValueException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException;
use SprykerFeature\Zed\ProductOption\Business\Exception\MissingProductOptionValueException;

interface DataImportWriterInterface
{

    /**
     * @param string $importKeyOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     *
     * @return int
     */
    public function importOptionType($importKeyOptionType, array $localizedNames = [], $importKeyTaxSet = null);

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
    public function importOptionValue($importKeyOptionValue, $importKeyOptionType, array $localizedNames = [], $price = null);

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
    public function importProductOptionType($sku, $importKeyOptionType, $isOptional = false, $sequence = null);

    /**
     * @param int $idProductOptionType
     * @param string $importKeyOptionValue
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionTypeException
     * @throws MissingOptionValueException
     */
    public function importProductOptionValue($idProductOptionType, $importKeyOptionValue, $sequence = null);

    /**
     * @param string $sku
     * @param string $importKeyOptionTypeA
     * @param string $importKeyOptionTypeB
     *
     * @throws MissingOptionTypeException
     * @throw MissingProductOptionTypeException
     */
    public function importProductOptionTypeExclusion($sku, $importKeyOptionTypeA, $importKeyOptionTypeB);

    /**
     * @param string $sku
     * @param int $idProductOptionValueSource
     * @param string $importKeyOptionValueTarget
     * @param string $operator
     *
     * @throws MissingProductOptionValueException
     * @throws MissingOptionValueException
     * @throws MissingProductOptionValueException
     */
    public function importProductOptionValueConstraint($sku, $idProductOptionValueSource, $importKeyOptionValueTarget, $operator);

    /**
     * @param $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @return int
     *
     * @throws MissingProductOptionValueException
     * @throws MissingOptionValueException
     * @throws MissingProductOptionValueException
     */
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null);
}
