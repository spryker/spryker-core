<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Business\Model;

use Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException;
use Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueException;
use Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException;
use Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException;

interface DataImportWriterInterface
{

    /**
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     *
     * @return int
     */
    public function importProductOptionType($importKeyProductOptionType, array $localizedNames = [], $importKeyTaxSet = null);

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
    public function importProductOptionValue($importKeyProductOptionValue, $importKeyProductOptionType, array $localizedNames = [], $price = null);

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
    public function importProductOptionTypeUsage($sku, $importKeyProductOptionType, $isOptional = false, $sequence = null);

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
    public function importProductOptionValueUsage($idProductOptionTypeUsage, $importKeyProductOptionValue, $sequence = null);

    /**
     * @param string $sku
     * @param string $importKeyProductOptionTypeA
     * @param string $importKeyProductOptionTypeB
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException
     */
    public function importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB);

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
    public function importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator);

    /**
     * @param string $sku
     * @param array $importKeysProductOptionValues
     * @param bool $isDefault
     * @param int $sequence
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     *
     * @return int
     */
    public function importPresetConfiguration($sku, array $importKeysProductOptionValues, $isDefault = false, $sequence = null);

    public function flushBuffer();

}
