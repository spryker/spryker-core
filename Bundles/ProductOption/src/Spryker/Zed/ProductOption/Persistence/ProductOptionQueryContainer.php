<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionConfigurationPresetTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionConfigurationPresetValueTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionTypeTranslationTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionTypeUsageExclusionTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionTypeUsageTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValuePriceTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTranslationTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueUsageConstraintTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueUsageTableMap;
use Orm\Zed\Tax\Persistence\Map\SpyTaxRateTableMap;

/**
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionPersistenceFactory getFactory()
 */
class ProductOptionQueryContainer extends AbstractQueryContainer implements ProductOptionQueryContainerInterface
{

    const VALUE_USAGE_ID = 'valueUsageId';
    const ID_VALUE_USAGE = 'idValueUsage';
    const ID_TYPE_USAGE = 'idTypeUsage';
    const PRESET_ID = 'presetId';
    const IS_DEFAULT = 'isDefault';
    const IS_OPTIONAL = 'isOptional';
    const SEQUENCE = 'sequence';
    const OPERATOR = 'operator';
    const EXCLUDES = 'excludes';
    const TAX_RATE = 'taxRate';
    const LABEL = 'label';
    const PRICE = 'price';

    /**
     * @param string $importKeyProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeQuery
     */
    public function queryProductOptionTypeByImportKey($importKeyProductOptionType)
    {
        return $this->getFactory()->createProductOptionTypeQuery()
            ->filterByImportKey($importKeyProductOptionType);
    }

    /**
     * @param int $fkProductOptionType
     * @param int $fkLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeTranslationQuery
     */
    public function queryProductOptionTypeTranslationByFks($fkProductOptionType, $fkLocale)
    {
        return $this->getFactory()->createProductOptionTypeTranslationQuery()
            ->filterByFkProductOptionType($fkProductOptionType)
            ->filterByFkLocale($fkLocale);
    }

    /**
     * @param string $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryOptionValueById($idProductOptionValue)
    {
        return $this->getFactory()->createProductOptionTypeUsageQuery()
            ->filterByIdProductOptionTypeUsage($idProductOptionValue);
    }

    /**
     * @param string $importKeyProductOptionValue
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKeyAndFkProductOptionType($importKeyProductOptionValue, $fkProductOptionType)
    {
        return $this->getFactory()->createProductOptionValueQuery()
            ->filterByImportKey($importKeyProductOptionValue)
            ->filterByFkProductOptionType($fkProductOptionType);
    }

    /**
     * @param string $importKeyProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueQuery
     */
    public function queryProductOptionValueByImportKey($importKeyProductOptionValue)
    {
        return $this->getFactory()->createProductOptionValueQuery()
            ->filterByImportKey($importKeyProductOptionValue);
    }

    /**
     * @param int $fkProductOptionValue
     * @param int $fkLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueTranslationQuery
     */
    public function queryProductOptionValueTranslationByFks($fkProductOptionValue, $fkLocale)
    {
        return $this->getFactory()->createProductOptionValueTranslationQuery()
            ->filterByFkProductOptionValue($fkProductOptionValue)
            ->filterByFkLocale($fkLocale);
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageById($idProductOptionTypeUsage)
    {
        return $this->getFactory()->createProductOptionTypeUsageQuery()
            ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage);
    }

    /**
     * @param int $fkProduct
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageByFKs($fkProduct, $fkProductOptionType)
    {
        return $this->getFactory()->createProductOptionTypeUsageQuery()
            ->filterByFkProduct($fkProduct)
            ->filterByFkProductOptionType($fkProductOptionType);
    }

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageById($idProductOptionValueUsage)
    {
        return $this->getFactory()->createProductOptionValueUsageQuery()
            ->filterByIdProductOptionValueUsage($idProductOptionValueUsage);
    }

    /**
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionValueUsageByFKs($fkProductOptionTypeUsage, $fkProductOptionValue)
    {
        return $this->getFactory()->createProductOptionValueUsageQuery()
            ->filterByFkProductOptionTypeUsage($fkProductOptionTypeUsage)
            ->filterByFkProductOptionValue($fkProductOptionValue);
    }

    /**
     * @param int $fkProductOptionTypeUsage
     * @param int $fkProductOptionType
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionValueUsageIdByFKs($fkProductOptionTypeUsage, $fkProductOptionType)
    {
        return $this->getFactory()->createProductOptionValueUsageQuery()
            ->filterByFkProductOptionTypeUsage($fkProductOptionTypeUsage)
            ->filterByFkProductOptionValue($fkProductOptionType);
    }

    /**
     * @param int $fkProductOptionTypeUsageA
     * @param int $fkProductOptionTypeUsageB
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageExclusionQuery
     */
    public function queryProductOptionTypeUsageExclusionByFks($fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB)
    {
        return $this->getFactory()->createProductOptionTypeUsageExclusionQuery()
            ->filterByFkProductOptionTypeUsageA([$fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB])
            ->filterByFkProductOptionTypeUsageB([$fkProductOptionTypeUsageA, $fkProductOptionTypeUsageB]);
    }

    /**
     * @param int $fkProductOptionValueUsageA
     * @param int $fkProductOptionValueUsageB
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageConstraintQuery
     */
    public function queryProductOptionValueUsageConstraintsByFks($fkProductOptionValueUsageA, $fkProductOptionValueUsageB)
    {
        return $this->getFactory()->createProductOptionValueUsageConstraintQuery()
            ->filterByFkProductOptionValueUsageA([$fkProductOptionValueUsageA, $fkProductOptionValueUsageB])
            ->filterByFkProductOptionValueUsageB([$fkProductOptionValueUsageA, $fkProductOptionValueUsageB]);
    }

    /**
     * @param int $idProductOptionType
     *
     * @return \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery
     */
    public function queryAssociatedProductAbstractIdsForProductOptionType($idProductOptionType)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->useSpyProductQuery()
                ->useSpyProductOptionTypeUsageQuery()
                    ->useSpyProductOptionTypeQuery()
                        ->filterByIdProductOptionType($idProductOptionType)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupByIdProductAbstract()
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT]);
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery
     */
    public function queryAssociatedProductAbstractIdsForProductOptionValue($idProductOptionValue)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->useSpyProductQuery()
                ->useSpyProductOptionTypeUsageQuery()
                    ->useSpyProductOptionValueUsageQuery()
                        ->useSpyProductOptionValueQuery()
                            ->filterByIdProductOptionValue($idProductOptionValue)
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->groupByIdProductAbstract()
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT]);
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return \Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery
     */
    public function queryProductAbstractIdForProductOptionTypeUsage($idProductOptionTypeUsage)
    {
        return $this->getFactory()->createProductAbstractQuery()
            ->useSpyProductQuery()
                ->useSpyProductOptionTypeUsageQuery()
                    ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage)
                ->endUse()
            ->endUse()
            ->groupByIdProductAbstract();
    }

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionConfigurationPresetQuery
     */
    public function queryPresetConfigurationsForProductConcrete($idProduct)
    {
        return $this->getFactory()->createProductOptionConfigurationPresetQuery()
            ->filterByFkProduct($idProduct)
            ->orderBySequence();
    }

    /**
     * @param int $idProductOptionValueUsage
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageWithAssociatedAttributes($idProductOptionValueUsage, $idLocale)
    {
        return $this->getFactory()->createProductOptionValueUsageQuery()
            ->useSpyProductOptionValueQuery()
                ->useSpyProductOptionValuePriceQuery()
                ->endUse()
                ->useSpyProductOptionTypeQuery()
                    ->useSpyProductOptionTypeTranslationQuery()
                        ->filterByFkLocale($idLocale)
                    ->endUse()
                ->endUse()
                ->useSpyProductOptionValueTranslationQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->filterByIdProductOptionValueUsage($idProductOptionValueUsage);
    }

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSetQuery
     */
    public function queryTaxSetForProductOptionValueUsage($idProductOptionValueUsage)
    {
        return $this->getFactory()->createTaxSetQuery()
            ->useSpyProductOptionTypeQuery()
                ->useSpyProductOptionValueQuery()
                    ->useSpyProductOptionValueUsageQuery()
                        ->filterByIdProductOptionValueUsage($idProductOptionValueUsage)
                    ->endUse()
                ->endUse()
            ->endUse();
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function queryTypeUsagesForProductConcrete($idProduct, $idLocale)
    {
        $query = $this->getFactory()->createProductOptionTypeUsageQuery()
            ->withColumn(SpyProductOptionTypeUsageTableMap::COL_ID_PRODUCT_OPTION_TYPE_USAGE, self::ID_TYPE_USAGE)
            ->withColumn(SpyProductOptionTypeUsageTableMap::COL_IS_OPTIONAL, self::IS_OPTIONAL)
            ->withColumn(SpyProductOptionTypeTranslationTableMap::COL_NAME, self::LABEL)
            ->useSpyProductOptionTypeQuery()
                ->useSpyProductOptionTypeTranslationQuery()
                    ->useSpyLocaleQuery()
                        ->filterByIdLocale($idLocale)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->filterByFkProduct($idProduct)
            ->orderBySequence()
            ->select([self::ID_TYPE_USAGE, self::IS_OPTIONAL, self::LABEL])
            ->find();

        $result = $query->toArray();

        return $result;
    }

    /**
     * @param int $idProductOptionTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function queryValueUsagesForTypeUsage($idProductOptionTypeUsage, $idLocale)
    {
        $query = $this->getFactory()->createProductOptionValueUsageQuery()
            ->withColumn(SpyProductOptionValueUsageTableMap::COL_ID_PRODUCT_OPTION_VALUE_USAGE, self::ID_VALUE_USAGE)
            ->withColumn(SpyProductOptionTypeUsageTableMap::COL_SEQUENCE, self::SEQUENCE)
            ->withColumn(SpyProductOptionValueTranslationTableMap::COL_NAME, self::LABEL)
            ->withColumn(SpyProductOptionValuePriceTableMap::COL_PRICE, self::PRICE)
            ->useSpyProductOptionValueQuery()
                ->leftJoinSpyProductOptionValuePrice()
                ->useSpyProductOptionValueTranslationQuery()
                    ->useSpyLocaleQuery()
                        ->filterByIdLocale($idLocale)
                    ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyProductOptionTypeUsageQuery()
                ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage)
            ->endUse()
            ->orderByIdProductOptionValueUsage()
            ->select([self::ID_VALUE_USAGE, self::SEQUENCE, self::LABEL, self::PRICE])
            ->find();

        $result = $query->toArray();

        return $result;
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return array
     */
    public function queryTypeExclusionsForTypeUsage($idProductOptionTypeUsage)
    {
        $queryA = $this->getFactory()->createProductOptionTypeUsageExclusionQuery()
            ->withColumn(SpyProductOptionTypeUsageExclusionTableMap::COL_FK_PRODUCT_OPTION_TYPE_USAGE_B, self::EXCLUDES)
            ->filterByFkProductOptionTypeUsageA($idProductOptionTypeUsage)
            ->select([self::EXCLUDES])
            ->find();

        $queryB = $this->getFactory()->createProductOptionTypeUsageExclusionQuery()
            ->withColumn(SpyProductOptionTypeUsageExclusionTableMap::COL_FK_PRODUCT_OPTION_TYPE_USAGE_A, self::EXCLUDES)
            ->filterByFkProductOptionTypeUsageB($idProductOptionTypeUsage)
            ->select([self::EXCLUDES])
            ->find();

        $result = array_merge($queryA->toArray(), $queryB->toArray());

        return $result;
    }

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsage($idProductOptionValueUsage)
    {
        $queryA = $this->getFactory()->createProductOptionValueUsageConstraintQuery()
            ->withColumn(SpyProductOptionValueUsageConstraintTableMap::COL_FK_PRODUCT_OPTION_VALUE_USAGE_B, self::VALUE_USAGE_ID)
            ->filterByFkProductOptionValueUsageA($idProductOptionValueUsage)
            ->orderByOperator()
            ->select([self::OPERATOR])
            ->find();

        $queryB = $this->getFactory()->createProductOptionValueUsageConstraintQuery()
            ->withColumn(SpyProductOptionValueUsageConstraintTableMap::COL_FK_PRODUCT_OPTION_VALUE_USAGE_A, self::VALUE_USAGE_ID)
            ->filterByFkProductOptionValueUsageB($idProductOptionValueUsage)
            ->orderByOperator()
            ->select([self::OPERATOR])
            ->find();

        $result = array_merge($queryA->toArray(), $queryB->toArray());

        return $result;
    }

    /**
     * @param int $idProductOptionValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsageByOperator($idProductOptionValueUsage, $operator)
    {
        $queryA = $this->getFactory()->createProductOptionValueUsageConstraintQuery()
            ->withColumn(SpyProductOptionValueUsageConstraintTableMap::COL_FK_PRODUCT_OPTION_VALUE_USAGE_B, self::VALUE_USAGE_ID)
            ->filterByFkProductOptionValueUsageA($idProductOptionValueUsage)
            ->filterByOperator($operator)
            ->select([self::OPERATOR])
            ->find();

        $queryB = $this->getFactory()->createProductOptionValueUsageConstraintQuery()
            ->withColumn(SpyProductOptionValueUsageConstraintTableMap::COL_FK_PRODUCT_OPTION_VALUE_USAGE_A, self::VALUE_USAGE_ID)
            ->filterByFkProductOptionValueUsageB($idProductOptionValueUsage)
            ->filterByOperator($operator)
            ->select([self::OPERATOR])
            ->find();

        $mergedArray = array_merge($queryA->toArray(), $queryB->toArray());

        $result = [];
        if (!empty($mergedArray)) {
            foreach ($mergedArray as $value) {
                $result[] = $value[self::VALUE_USAGE_ID];
            }
        }

        return $result;
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function queryConfigPresetsForProductConcrete($idProduct)
    {
        $query = $this->getFactory()->createProductOptionConfigurationPresetQuery()
            ->withColumn(SpyProductOptionConfigurationPresetTableMap::COL_IS_DEFAULT, self::IS_DEFAULT)
            ->withColumn(SpyProductOptionConfigurationPresetTableMap::COL_ID_PRODUCT_OPTION_CONFIGURATION_PRESET, self::PRESET_ID)
            ->filterByFkProduct($idProduct)
            ->orderBySequence()
            ->select([self::PRESET_ID, self::IS_DEFAULT])
            ->find();

        $result = $query->toArray();

        return $result;
    }

    /**
     * @param int $idProductOptionConfigurationPreset
     *
     * @return array
     */
    public function queryValueUsagesForConfigPreset($idProductOptionConfigurationPreset)
    {
        $query = $this->getFactory()->createProductOptionConfigurationPresetValueQuery()
            ->withColumn(SpyProductOptionConfigurationPresetValueTableMap::COL_FK_PRODUCT_OPTION_VALUE_USAGE, self::VALUE_USAGE_ID)
            ->filterByFkProductOptionConfigurationPreset($idProductOptionConfigurationPreset)
            ->select([self::VALUE_USAGE_ID])
            ->find();

        $result = $query->toArray();

        return $result;
    }

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionTypeUsageQuery
     */
    public function queryProductOptionTypeUsageByIdProduct($idProduct)
    {
        $query = $this->getFactory()->createProductOptionTypeUsageQuery();
        $query->filterByFkProduct($idProduct)
            ->setDistinct();

        return $query;
    }

    /**
     * @param int $idProductOptionTypeUsage
     *
     * @return string|null
     */
    public function queryEffectiveTaxRateForTypeUsage($idProductOptionTypeUsage)
    {
        $query = $this->getFactory()->createProductOptionTypeUsageQuery()
            ->withColumn('SUM(' . SpyTaxRateTableMap::COL_RATE . ')', self::TAX_RATE)
            ->useSpyProductOptionTypeQuery()
                ->useSpyTaxSetQuery()
                    ->useSpyTaxSetTaxQuery()
                        ->useSpyTaxRateQuery()
                        ->endUse()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage)
            ->select([self::TAX_RATE])
            ->find();

        $result = $query->getFirst();

        return $result;
    }

}
