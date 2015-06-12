<?php

namespace SprykerFeature\Zed\ProductOption\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Product\Persistence\Propel\Base\SpyAbstractProductQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageExclusionQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageConstraintQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyProductOptionTypeTableMap;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyProductOptionValueTableMap;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyProductOptionTypeUsageTableMap;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyProductOptionValueUsageTableMap;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;

class ProductOptionQueryContainer extends AbstractQueryContainer implements ProductOptionQueryContainerInterface
{
    /**
     * @param string $importKeyProductOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeByImportKey($importKeyProductOptionType)
    {
        return SpyProductOptionTypeQuery::create()
            ->filterByImportKey($importKeyProductOptionType);
    }

    /**
     * @param string $importKeyProductOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeIdByImportKey($importKeyProductOptionType)
    {
        return SpyProductOptionTypeQuery::create()
            ->filterByImportKey($importKeyProductOptionType)
            ->select(SpyProductOptionTypeTableMap::COL_ID_PRODUCT_OPTION_TYPE);
    }

    /**
     * @param int $fkProductOptionType
     * @param int $fkLocale
     *
     * @return SpyProductOptionTypeTranslationQuery
     */
    public function queryProductOptionTypeTranslationByFks($fkProductOptionType, $fkLocale)
    {
        return SpyProductOptionTypeTranslationQuery::create()
            ->filterByFkProductOptionType($fkProductOptionType)
            ->filterByFkLocale($fkLocale);
    }

    /**
     * @param string $importKeyProductOptionValue
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryOptionValueById($idProductOptionTypeUsage)
    {
        return SpyProductOptionTypeUsageQuery::create()
            ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage);
    }

    /**
     * @param string $importKeyProductOptionValue
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $fkProductOptionType)
    {
        return SpyProductOptionValueQuery::create()
            ->filterByImportKey($importKeyProductOptionValue)
            ->filterByFkProductOptionType($fkProductOptionType);
    }

    /**
     * @param string $importKeyProductOptionValue
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKey($importKeyProductOptionValue)
    {
        return SpyProductOptionValueQuery::create()
            ->filterByImportKey($importKeyProductOptionValue);
    }

    /**
     * @param string $importKeyProductOptionValue
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptionValueIdByImportKey($importKeyProductOptionValue)
    {
        return SpyProductOptionValueQuery::create()
            ->filterByImportKey($importKeyProductOptionValue)
            ->select(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE);
    }

    /**
     * @param int $fkProductOptionValue
     * @param int $fkLocale
     *
     * @return SpyProductOptionValueTranslationQuery
     */
    public function queryProductOptionValueTranslationByFks($fkProductOptionValue, $fkLocale)
    {
        return SpyProductOptionValueTranslationQuery::create()
            ->filterByFkProductOptionValue($fkProductOptionValue)
            ->filterByFkLocale($fkLocale);
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptonTypeUsageById($idProductOptionTypeUsage)
    {
        return SpyProductOptionTypeUsageQuery::create()
            ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage);
    }

    /**
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageByFKs($fkProduct, $fkProductOptionType)
    {
        return SpyProductOptionTypeUsageQuery::create()
            ->filterByFkProduct($fkProduct)
            ->filterByFkProductOptionType($fkProductOptionType);
    }

    /**
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageIdByFKs($fkProduct, $fkProductOptionType)
    {
        return SpyProductOptionTypeUsageQuery::create()
            ->filterByFkProduct($fkProduct)
            ->filterByFkProductOptionType($fkProductOptionType)
            ->select(SpyProductOptionTypeUsageTableMap::COL_ID_PRODUCT_OPTION_TYPE_USAGE);
    }

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return SpyProductOptionValueUsageQuery
     */
    public function queryProductOptonValueUsageById($idProductOptionValueUsage)
    {
        return SpyProductOptionValueUsageQuery::create()
            ->filterByIdProductOptionValueUsage($idProductOptionValueUsage);
    }

    /**
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionValueUsageByFKs($fkProductOptionTypeUsage, $fkProductOptionType)
    {
        return SpyProductOptionValueUsageQuery::create()
            ->filterByFkProductOptionTypeUsage($fkProductOptionTypeUsage)
            ->filterByFkProductOptionValue($fkProductOptionType);
    }

    /**
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionValueUsageIdByFKs($fkProductOptionTypeUsage, $fkProductOptionType)
    {
        return SpyProductOptionValueUsageQuery::create()
            ->filterByFkProductOptionTypeUsage($fkProductOptionTypeUsage)
            ->filterByFkProductOptionValue($fkProductOptionType)
            ->select(SpyProductOptionValueUsageTableMap::COL_ID_PRODUCT_OPTION_VALUE_USAGE);
    }

    /**
     * @param int $fkProductOptionTypeUsageA
     * @param int $fkProductOptionTypeUsageB
     *
     * @return SpyProductOptionTypeUsageExclusionQuery
     */
    public function queryProductOptionTypeUsageExclusionByFks($fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB)
    {
        return SpyProductOptionTypeUsageExclusionQuery::create()
            ->filterByFkProductOptionTypeUsageA([$fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB])
            ->filterByFkProductOptionTypeUsageB([$fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB]);
    }

    /**
     * @param int $fkProductOptionValueUsageA
     * @param int $fkProductOptionValueUsageB
     *
     * @return SpyProductOptionValueUsageConstraintQuery
     */
    public function queryProductOptionValueUsageConstraintsByFks($fkProductOptionValueUsageA, $fkProductOptionValueUsageB)
    {
        return SpyProductOptionValueUsageConstraintQuery::create()
            ->filterByFkProductOptionValueUsageA([$fkProductOptionValueUsageA, $fkProductOptionValueUsageB])
            ->filterByFkProductOptionValueUsageB([$fkProductOptionValueUsageA, $fkProductOptionValueUsageB]);
    }

    /**
     * @param int $idProductOptionType
     *
     * @return SpyAbstractProductQuery
     */
    public function queryAssociatedAbstractProductIdsForProductOptionType($idProductOptionType)
    {
        return SpyAbstractProductQuery::create()
            ->select([
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT
            ])
            ->useSpyProductQuery()
                ->useSpyProductOptionTypeUsageQuery()
                    ->useSpyProductOptionTypeQuery()
                        ->filterByIdProductOptionType($idProductOptionType)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupByIdAbstractProduct();
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return SpyAbstractProductQuery
     */
    public function queryAssociatedAbstractProductIdsForProductOptionValue($idProductOptionValue)
    {
        return SpyAbstractProductQuery::create()
            ->select([
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT
            ])
            ->useSpyProductQuery()
                ->useSpyProductOptionTypeUsageQuery()
                    ->useSpyProductOptionValueUsageQuery()
                        ->useSpyProductOptionValueQuery()
                            ->filterByIdProductOptionValue($idProductOptionValue)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupByIdAbstractProduct();
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return SpyAbstractProductQuery
     */
    public function queryAbstractProductIdForProductOptionTypeUsage($idProductOptionTypeUsage)
    {
        return SpyAbstractProductQuery::create()
            ->select([
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT
            ])
            ->useSpyProductQuery()
                ->useSpyProductOptionTypeUsageQuery()
                    ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage)
                ->endUse()
            ->endUse()
            ->groupByIdAbstractProduct();
    }
}
