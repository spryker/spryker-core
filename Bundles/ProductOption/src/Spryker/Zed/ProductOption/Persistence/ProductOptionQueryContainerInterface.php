<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionTypeUsageExclusionQuery;
use Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionValueUsageConstraintQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeTranslationQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueTranslationQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Tax\Persistence\Base\SpyTaxSetQuery;

interface ProductOptionQueryContainerInterface
{

    /**
     * @param string $importKeyProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeByImportKey($importKeyProductOptionType);

    /**
     * @param int $fkProductOptionType
     * @param int $fkLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeTranslationQuery
     */
    public function queryProductOptionTypeTranslationByFks($fkProductOptionType, $fkLocale);

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryOptionValueById($idProductOptionValue);

    /**
     * @param string $importKeyProductOptionValue
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $fkProductOptionType);

    /**
     * @param string $importKeyProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKey($importKeyProductOptionValue);

    /**
     * @param int $fkProductOptionValue
     * @param int $fkLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueTranslationQuery
     */
    public function queryProductOptionValueTranslationByFks($fkProductOptionValue, $fkLocale);

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageById($idProductOptionTypeUsage);

    /**
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageByFKs($fkProduct, $fkProductOptionType);

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageById($idProductOptionValueUsage);

    /**
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageByFKs($fkProductOptionTypeUsage, $fkProductOptionValue);

    /**
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageIdByFKs($fkProductOptionTypeUsage, $fkProductOptionType);

    /**
     * @param int $fkProductOptionTypeUsageA
     * @param int $fkProductOptionTypeUsageB
     *
     * @return \Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionTypeUsageExclusionQuery
     */
    public function queryProductOptionTypeUsageExclusionByFks($fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB);

    /**
     * @param int $fkProductOptionValueUsageA
     * @param int $fkProductOptionValueUsageB
     *
     * @return \Orm\Zed\ProductOption\Persistence\Base\SpyProductOptionValueUsageConstraintQuery
     */
    public function queryProductOptionValueUsageConstraintsByFks($fkProductOptionValueUsageA, $fkProductOptionValueUsageB);

    /**
     * @param int $idProductOptionType
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAssociatedProductAbstractIdsForProductOptionType($idProductOptionType);

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAssociatedProductAbstractIdsForProductOptionValue($idProductOptionValue);

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractIdForProductOptionTypeUsage($idProductOptionTypeUsage);

    /**
     * @param int $idProductOptionValueUsage
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageWithAssociatedAttributes($idProductOptionValueUsage, $idLocale);

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return \Orm\Zed\Tax\Persistence\Base\SpyTaxSetQuery
     */
    public function queryTaxSetForProductOptionValueUsage($idProductOptionValueUsage);

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function queryTypeUsagesForProductConcrete($idProduct, $idLocale);

    /**
     * @param int $idProductOptionTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function queryValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale);

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return array
     */
    public function queryTypeExclusionsForTypeUsage($idProductOptionTypeUsage);

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsage($idProductOptionValueUsage);

    /**
     * @param int $idProductOptionValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsageByOperator($idProductOptionValueUsage, $operator);

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function queryConfigPresetsForProductConcrete($idProduct);

    /**
     * @param int $idProductOptionConfigurationPreset
     *
     * @return array
     */
    public function queryValueUsagesForConfigPreset($idProductOptionConfigurationPreset);

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return string|null
     */
    public function queryEffectiveTaxRateForTypeUsage($idProductOptionTypeUsage);

}
