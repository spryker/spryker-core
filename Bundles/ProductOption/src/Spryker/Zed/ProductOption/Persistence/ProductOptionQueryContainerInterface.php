<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Persistence;

interface ProductOptionQueryContainerInterface
{

    /**
     * @api
     *
     * @param string $importKeyProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeByImportKey($importKeyProductOptionType);

    /**
     * @api
     *
     * @param int $fkProductOptionType
     * @param int $fkLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeTranslationQuery
     */
    public function queryProductOptionTypeTranslationByFks($fkProductOptionType, $fkLocale);

    /**
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryOptionValueById($idProductOptionValue);

    /**
     * @api
     *
     * @param string $importKeyProductOptionValue
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $fkProductOptionType);

    /**
     * @api
     *
     * @param string $importKeyProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKey($importKeyProductOptionValue);

    /**
     * @api
     *
     * @param int $fkProductOptionValue
     * @param int $fkLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueTranslationQuery
     */
    public function queryProductOptionValueTranslationByFks($fkProductOptionValue, $fkLocale);

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageById($idProductOptionTypeUsage);

    /**
     * @api
     *
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageByFKs($fkProduct, $fkProductOptionType);

    /**
     * @api
     *
     * @param int $idProductOptionValueUsage
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageById($idProductOptionValueUsage);

    /**
     * @api
     *
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageByFKs($fkProductOptionTypeUsage, $fkProductOptionValue);

    /**
     * @api
     *
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageIdByFKs($fkProductOptionTypeUsage, $fkProductOptionType);

    /**
     * @api
     *
     * @param int $fkProductOptionTypeUsageA
     * @param int $fkProductOptionTypeUsageB
     *
     * @return \Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionTypeUsageExclusionQuery
     */
    public function queryProductOptionTypeUsageExclusionByFks($fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB);

    /**
     * @api
     *
     * @param int $fkProductOptionValueUsageA
     * @param int $fkProductOptionValueUsageB
     *
     * @return \Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionValueUsageConstraintQuery
     */
    public function queryProductOptionValueUsageConstraintsByFks($fkProductOptionValueUsageA, $fkProductOptionValueUsageB);

    /**
     * @api
     *
     * @param int $idProductOptionType
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAssociatedProductAbstractIdsForProductOptionType($idProductOptionType);

    /**
     * @api
     *
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAssociatedProductAbstractIdsForProductOptionValue($idProductOptionValue);

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractIdForProductOptionTypeUsage($idProductOptionTypeUsage);

    /**
     * @api
     *
     * @param int $idProductOptionValueUsage
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageWithAssociatedAttributes($idProductOptionValueUsage, $idLocale);

    /**
     * @api
     *
     * @param int $idProductOptionValueUsage
     *
     * @return \Orm\Zed\Tax\Persistence\Base\SpyTaxSetQuery
     */
    public function queryTaxSetForProductOptionValueUsage($idProductOptionValueUsage);

    /**
     * @api
     *
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function queryTypeUsagesForProductConcrete($idProduct, $idLocale);

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function queryValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale);

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     *
     * @return array
     */
    public function queryTypeExclusionsForTypeUsage($idProductOptionTypeUsage);

    /**
     * @api
     *
     * @param int $idProductOptionValueUsage
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsage($idProductOptionValueUsage);

    /**
     * @api
     *
     * @param int $idProductOptionValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsageByOperator($idProductOptionValueUsage, $operator);

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return array
     */
    public function queryConfigPresetsForProductConcrete($idProduct);

    /**
     * @api
     *
     * @param int $idProductOptionConfigurationPreset
     *
     * @return array
     */
    public function queryValueUsagesForConfigPreset($idProductOptionConfigurationPreset);

    /**
     * @api
     *
     * @param int $idProductOptionTypeUsage
     *
     * @return string|null
     */
    public function queryEffectiveTaxRateForTypeUsage($idProductOptionTypeUsage);

    /**
     * @api
     *
     * @param int[] $idsProductOptionTypeUsage
     * @param string $iso2Code
     *
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    public function queryTaxSetByProductOptionTypeUsageAndCountry($idsProductOptionTypeUsage, $iso2Code);

}
