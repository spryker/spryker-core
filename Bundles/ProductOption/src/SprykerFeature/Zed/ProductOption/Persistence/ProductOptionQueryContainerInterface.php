<?php

namespace SprykerFeature\Zed\ProductOption\Persistence;

use SprykerFeature\Zed\ProductOption\Persistence\Propel\Base\SpyProductOptionTypeUsageExclusionQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\Base\SpyProductOptionValueUsageConstraintQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageQuery;

interface ProductOptionQueryContainerInterface
{
    /**
     * @param string $importKeyProductOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeByImportKey($importKeyProductOptionType);

    /**
     * @param string $importKeyProductOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeIdByImportKey($importKeyProductOptionType);

    /**
     * @param int $fkProductOptionType
     * @param int $fkLocale
     *
     * @return SpyProductOptionTypeTranslationQuery
     */
    public function queryProductOptionTypeTranslationByFks($fkProductOptionType, $fkLocale);

    /**
     * @param string $importKeyProductOptionValue
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $fkProductOptionType);

    /**
     * @param string $importKeyProductOptionValue
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKey($importKeyProductOptionValue);

    /**
     * @param string $importKeyProductOptionValue
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptionValueIdByImportKey($importKeyProductOptionValue);

    /**
     * @param int $fkProductOptionValue
     * @param int $fkLocale
     *
     * @return SpyProductOptionValueTranslationQuery
     */
    public function queryProductOptionValueTranslationByFks($fkProductOptionValue, $fkLocale);

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptonTypeUsageById($idProductOptionTypeUsage);

    /**
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageByFKs($fkProduct, $fkProductOptionType);

    /**
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageIdByFKs($fkProduct, $fkProductOptionType);

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return SpyProductOptionValueUsageQuery
     */
    public function queryProductOptonValueUsageById($idProductOptionValueUsage);

    /**
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageByFKs($fkProductOptionTypeUsage, $fkProductOptionType);

    /**
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionType
     *
     * @return SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageIdByFKs($fkProductOptionTypeUsage, $fkProductOptionType);

    /**
     * @param int $fkProductOptionTypeUsageA
     * @param int $fkProductOptionTypeUsageB
     *
     * @return SpyProductOptionTypeUsageExclusionQuery
     */
    public function queryProductOptionTypeUsageExclusionByFks($fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB);

    /**
     * @param int $fkProductOptionValueUsageA
     * @param int $fkProductOptionValueUsageB
     *
     * @return SpyProductOptionValueUsageConstraintQuery
     */
    public function queryProductOptionValueUsageConstraintsByFks($fkProductOptionValueUsageA, $fkProductOptionValueUsageB);
}
