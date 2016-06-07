<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

interface ProductImageFacadeInterface
{

    /**
     * @api
     *
     * @param int $idProductImageValueUsage
     * @param string $localeCode
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function getProductImage($idProductImageValueUsage, $localeCode);

    /**
     * @api
     *
     * @param int $idProduct
     * @param string $localeCode
     *
     * @return mixed
     */
    public function getProductImagesByIdProduct($idProduct, $localeCode);

    /**
     * @api
     *
     * @param string $importKeyProductImageType
     * @param array $localizedNames
     * @param string|null $importKeyTaxSet
     *
     * @return int
     */
    public function importProductImageType($importKeyProductImageType, array $localizedNames = [], $importKeyTaxSet = null);

    /**
     * @api
     *
     * @param string $importKeyProductImageValue
     * @param string $importKeyProductImageType
     * @param array $localizedNames
     * @param float|null $price
     *
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageTypeException
     *
     * @return int
     */
    public function importProductImageValue($importKeyProductImageValue, $importKeyProductImageType, array $localizedNames = [], $price = null);

    /**
     * @api
     *
     * @param string $sku
     * @param string $importKeyProductImageType
     * @param bool $isOptional
     * @param int|null $sequence
     *
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageTypeException
     *
     * @return int
     */
    public function importProductImageTypeUsage($sku, $importKeyProductImageType, $isOptional = false, $sequence = null);

    /**
     * @api
     *
     * @param int $idProductImageTypeUsage
     * @param string $importKeyProductImageValue
     * @param int|null $sequence
     *
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageTypeUsageException
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageValueException
     *
     * @return int
     */
    public function importProductImageValueUsage($idProductImageTypeUsage, $importKeyProductImageValue, $sequence = null);

    /**
     * @api
     *
     * @param string $sku
     * @param string $importKeyProductImageTypeA
     * @param string $importKeyProductImageTypeB
     *
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageTypeException
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageTypeUsageException
     *
     * @return void
     */
    public function importProductImageTypeUsageExclusion($sku, $importKeyProductImageTypeA, $importKeyProductImageTypeB);

    /**
     * @api
     *
     * @param string $sku
     * @param int $idProductImageValueUsageSource
     * @param string $importKeyProductImageValueTarget
     * @param string $operator
     *
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageValueUsageException
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageValueException
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageValueUsageException
     *
     * @return void
     */
    public function importProductImageValueUsageConstraint($sku, $idProductImageValueUsageSource, $importKeyProductImageValueTarget, $operator);

    /**
     * @api
     *
     * @param string $sku
     * @param array $importKeysOptionValues
     * @param bool $isDefault
     * @param int|null $sequence
     *
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageValueUsageException
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageValueException
     * @throws \Spryker\Zed\ProductImage\Business\Exception\MissingProductImageValueUsageException
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
     * @param int $idProductImageTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function getValueUsagesForTypeUsage($idProductImageTypeUsage, $idLocale);

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
