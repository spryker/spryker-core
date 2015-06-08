<?php

namespace SprykerFeature\Zed\ProductOptions\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Base\SpyProductOptionTypeExclusionQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Base\SpyProductOptionValueConstraintQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionTypeQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValueQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionValueQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProductQuery;

class ProductOptionsQueryContainer extends AbstractQueryContainer implements ProductOptionsQueryContainerInterface
{
    /**
     * @param string $sku
     *
     * @return SpyProductQuery
     */
    public function queryProductBySku($sku)
    {
        return SpyProductQuery::create()
            ->filterBySku($sku);
    }

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
