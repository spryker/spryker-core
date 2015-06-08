<?php

namespace SprykerFeature\Zed\ProductOptions\Persistence;

use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Base\SpyProductOptionTypeExclusionQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\Base\SpyProductOptionValueConstraintQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionTypeQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValueQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionTypeTranslationQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyOptionValueTranslationQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOptions\Persistence\Propel\SpyProductOptionValueQuery;

interface ProductOptionsQueryContainerInterface
{
    /**
     * @param string $importKeyOptionType
     *
     * @return SpyOptionTypeQuery
     */
    public function queryOptionTypeByImportKey($importKeyOptionType);

    /**
     * @param int $fkOptionType
     * @param int $fkLocale
     *
     * @return SpyOptionTypeTranslationQuery
     */
    public function queryOptionTypeTranslationByFks($fkOptionType, $fkLocale);

    /**
     * @param string $importKeyOptionValue
     * @param int $fkOptionType
     *
     * @return SpyOptionValueQuery
     */
    public function queryOptionValueByImportKeyAndFkOptionType($importKeyOptionValue, $fkOptionType);

    /**
     * @param string $importKeyOptionValue
     *
     * @return SpyOptionValueQuery
     */
    public function queryOptionValueByImportKey($importKeyOptionValue);

    /**
     * @param int $fkOptionValue
     * @param int $fkLocale
     *
     * @return SpyOptionValueTranslationQuery
     */
    public function queryOptionValueTranslationByFks($fkOptionValue, $fkLocale);

    /**
     * @param int $idProductOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptonTypeById($idProductOptionType);

    /**
     * @param int $fkProduct
     * @param int $fkOptionType
     *
     * @return SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeByFKs($fkProduct, $fkOptionType);

    /**
     * @param int $idProductOptionValue
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptonValueById($idProductOptionValue);

    /**
     * @param int $fkProductOptionType
     * @param int $fkOptionType
     *
     * @return SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByFKs($fkProductOptionType, $fkOptionType);

    /**
     * @param int $fkProductOptionTypeA
     * @param int $fkProductOptionTypeB
     *
     * @return SpyProductOptionTypeExclusionQuery
     */
    public function queryProductOptionTypeExclusionByFks($fkProductOptionTypeA, $fkProductOptionTypeB);

    /**
     * @param int $fkProductOptionValueA
     * @param int $fkProductOptionValueB
     *
     * @return SpyProductOptionValueConstraintQuery
     */
    public function queryProductOptionValueConstraintsByFks($fkProductOptionValueA, $fkProductOptionValueB);
}
