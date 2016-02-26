<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business;

interface ProductOptionFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductOptionValueUsage
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValueUsage, $localeCode);

    /**
     * @api
     *
     * @param int $idProduct
     * @param string $localeCode
     *
     * @return mixed
     */
    public function getProductOptionsByIdProduct($idProduct, $localeCode);

    /**
     * @api
     *
     * @param string $importKeyProductOptionType
     * @param array $localizedNames
     * @param string $importKeyTaxSet
     *
     * @return int
     */
    public function importProductOptionType($importKeyProductOptionType, array $localizedNames = [], $importKeyTaxSet = null);

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @param string $sku
     * @param string $importKeyProductOptionTypeA
     * @param string $importKeyProductOptionTypeB
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionTypeUsageException
     *
     * @return void
     */
    public function importProductOptionTypeUsageExclusion($sku, $importKeyProductOptionTypeA, $importKeyProductOptionTypeB);

    /**
     * @api
     *
     * @param string $sku
     * @param int $idProductOptionValueUsageSource
     * @param string $importKeyProductOptionValueTarget
     * @param string $operator
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueException
     * @throws \Spryker\Zed\ProductOption\Business\Exception\MissingProductOptionValueUsageException
     *
     * @return void
     */
    public function importProductOptionValueUsageConstraint($sku, $idProductOptionValueUsageSource, $importKeyProductOptionValueTarget, $operator);

    /**
     * @api
     *
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
    public function importPresetConfiguration($sku, array $importKeysOptionValues, $isDefault = false, $sequence = null);

    /**
     * @api
     *
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function getTypeUsagesForProductConcrete($idProduct, $idLocale);

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale);

    /**
     * @api
     *
     * @param int $idProductAttributeTypeUsage
     *
     * @return array
     */
    public function getTypeExclusionsForTypeUsage($idProductAttributeTypeUsage);

    /**
     * @api
     *
     * @param int $idValueUsage
     *
     * @return array
     */
    public function getValueConstraintsForValueUsage($idValueUsage);

    /**
     * @api
     *
     * @param int $idValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function getValueConstraintsForValueUsageByOperator($idValueUsage, $operator);

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function getConfigPresetsForProductConcrete($idProduct);

    /**
     * @api
     *
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function getValueUsagesForConfigPreset($idConfigPreset);

    /**
     * @api
     *
     * @param int $idProductAttributeTypeUsage
     *
     * @return string|null
     */
    public function getEffectiveTaxRateForTypeUsage($idProductAttributeTypeUsage);

    /**
     * @api
     *
     * @return void
     */
    public function flushBuffer();

}
