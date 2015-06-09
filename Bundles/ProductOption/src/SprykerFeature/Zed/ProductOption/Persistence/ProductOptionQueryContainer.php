<?php

namespace SprykerFeature\Zed\ProductOption\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Base\SpyProductOptionTypeExclusionQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Base\SpyProductOptionValueConstraintQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyOptionValueQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyOptionTypeTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyOptionValueTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyOptionTypeTableMap;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyOptionValueTableMap;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyProductOptionTypeTableMap;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Map\SpyProductOptionValueTableMap;

class ProductOptionQueryContainer extends AbstractQueryContainer implements ProductOptionQueryContainerInterface
{
    /**
     * @param string $importKeyOptionType
     *
     * @return SpyOptionTypeQuery
     */
    public function queryOptionTypeByImportKey($importKeyOptionType)
    {
        return SpyOptionTypeQuery::create()
            ->filterByImportKey($importKeyOptionType);
    }

    /**
     * @param string $importKeyOptionType
     *
     * @return SpyOptionTypeQuery
     */
    public function queryOptionTypeIdByImportKey($importKeyOptionType)
    {
        return SpyOptionTypeQuery::create()
            ->filterByImportKey($importKeyOptionType)
            ->select(SpyOptionTypeTableMap::COL_ID_OPTION_TYPE);
    }

    /**
     * @param int $fkOptionType
     * @param int $fkLocale
     *
     * @return SpyOptionTypeTranslationQuery
     */
    public function queryOptionTypeTranslationByFks($fkOptionType, $fkLocale)
    {
        return SpyOptionTypeTranslationQuery::create()
            ->filterByFkOptionType($fkOptionType)
            ->filterByFkLocale($fkLocale);
    }

    /**
     * @param string $importKeyOptionValue
     *
     * @return SpyOptionValueQuery
     */
    public function queryOptionValueById($idProductOptionType)
    {
        return SpyProductOptionTypeQuery::create()
            ->filterByIdProductOptionType($idProductOptionType);
    }

    /**
     * @param string $importKeyOptionValue
     * @param int $fkOptionType
     *
     * @return SpyOptionValueQuery
     */
    public function queryOptionValueByImportKeyAndFkOptionType($importKeyOptionValue, $fkOptionType)
    {
        return SpyOptionValueQuery::create()
            ->filterByImportKey($importKeyOptionValue)
            ->filterByFkOptionType($fkOptionType);
    }

    /**
     * @param string $importKeyOptionValue
     *
     * @return SpyOptionValueQuery
     */
    public function queryOptionValueByImportKey($importKeyOptionValue)
    {
        return SpyOptionValueQuery::create()
            ->filterByImportKey($importKeyOptionValue);
    }

    /**
     * @param string $importKeyOptionValue
     *
     * @return SpyOptionValueQuery
     */
    public function queryOptionValueIdByImportKey($importKeyOptionValue)
    {
        return SpyOptionValueQuery::create()
            ->filterByImportKey($importKeyOptionValue)
            ->select(SpyOptionValueTableMap::COL_ID_OPTION_VALUE);
    }

    /**
     * @param int $fkOptionValue
     * @param int $fkLocale
     *
     * @return SpyOptionValueTranslationQuery
     */
    public function queryOptionValueTranslationByFks($fkOptionValue, $fkLocale)
    {
        return SpyOptionValueTranslationQuery::create()
            ->filterByFkOptionValue($fkOptionValue)
            ->filterByFkLocale($fkLocale);
    }

    /**
     * @param int $idProductOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptonTypeById($idProductOptionType)
    {
        return SpyProductOptionTypeQuery::create()
            ->filterByIdProductOptionType($idProductOptionType);
    }

    /**
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeByFKs($fkProduct, $fkOptionType)
    {
        return SpyProductOptionTypeQuery::create()
            ->filterByFkProduct($fkProduct)
            ->filterByFkOptionType($fkOptionType);
    }

    /**
     * @param int $fkProduct
     * @param int $fkOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeIdByFKs($fkProduct, $fkOptionType)
    {
        return SpyProductOptionTypeQuery::create()
            ->filterByFkProduct($fkProduct)
            ->filterByFkOptionType($fkOptionType)
            ->select(SpyProductOptionTypeTableMap::COL_ID_PRODUCT_OPTION_TYPE);
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptonValueById($idProductOptionValue)
    {
        return SpyProductOptionValueQuery::create()
            ->filterByIdProductOptionValue($idProductOptionValue);
    }

    /**
     * @param int $fkProduct
     * @param int $fkOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionValueByFKs($fkProductOptionType, $fkOptionType)
    {
        return SpyProductOptionValueQuery::create()
            ->filterByFkProductOptionType($fkProductOptionType)
            ->filterByFkOptionValue($fkOptionType);
    }

    /**
     * @param int $fkProductOptionType
     * @param int $fkOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionValueIdByFKs($fkProductOptionType, $fkOptionType)
    {
        return SpyProductOptionValueQuery::create()
            ->filterByFkProductOptionType($fkProductOptionType)
            ->filterByFkOptionValue($fkOptionType)
            ->select(SpyProductOptionValueTableMap::COL_ID_PRODUCT_OPTION_VALUE);
    }

    /**
     * @param int $fkProductOptionTypeA
     * @param int $fkProductOptionTypeB
     *
     * @return SpyProductOptionTypeExclusionQuery
     */
    public function queryProductOptionTypeExclusionByFks($fkProductOptionTypeA, $fkProductOptionTypeB)
    {
        return SpyProductOptionTypeExclusionQuery::create()
            ->filterByFkProductOptionTypeA([$fkProductOptionTypeA, $fkProductOptionTypeB])
            ->filterByFkProductOptionTypeB([$fkProductOptionTypeA, $fkProductOptionTypeB]);
    }

    /**
     * @param int $fkProductOptionValueA
     * @param int $fkProductOptionValueB
     *
     * @return SpyProductOptionValueConstraintQuery
     */
    public function queryProductOptionValueConstraintsByFks($fkProductOptionValueA, $fkProductOptionValueB)
    {
        return SpyProductOptionValueConstraintQuery::create()
            ->filterByFkProductOptionValueA([$fkProductOptionValueA, $fkProductOptionValueB])
            ->filterByFkProductOptionValueB([$fkProductOptionValueA, $fkProductOptionValueB]);
    }
}
