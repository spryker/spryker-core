<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionPersistence;
use SprykerFeature\Zed\Product\Persistence\Propel\Base\SpyAbstractProductQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionConfigurationPresetQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageExclusionQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageConstraintQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueTranslationQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageQuery;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageQuery;
use SprykerFeature\Zed\Product\Persistence\Propel\Map\SpyAbstractProductTableMap;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Propel;

/**
 * @method ProductOptionPersistence getFactory()
 */
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
        ;
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
        ;
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
    public function queryProductOptionTypeUsageById($idProductOptionTypeUsage)
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
        ;
    }

    /**
     * @param int $idProductOptionValueUsage
     *
     * @return SpyProductOptionValueUsageQuery
     */
    public function queryProductOptionValueUsageById($idProductOptionValueUsage)
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
            ->filterByFkProductOptionValue($fkProductOptionType)
        ;
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
        ;
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
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
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
                SpyAbstractProductTableMap::COL_ID_ABSTRACT_PRODUCT,
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
            ->useSpyProductQuery()
                ->useSpyProductOptionTypeUsageQuery()
                    ->filterByIdProductOptionTypeUsage($idProductOptionTypeUsage)
                ->endUse()
            ->endUse()
            ->groupByIdAbstractProduct();
    }

    /**
     * @param int $idProduct
     *
     * @return SpyProductOptionConfigurationPresetQuery
     */
    public function queryPresetConfigurationsForConcreteProduct($idProduct)
    {
        return SpyProductOptionConfigurationPresetQuery::create()
            ->filterByFkProduct($idProduct)
            ->orderBySequence();
    }

    /**
     * @param int $idProduct
     * @param int $idLocale
     *
     * @return array
     */
    public function queryTypeUsagesForConcreteProduct($idProduct, $idLocale)
    {
        $sql =
            'SELECT
            typeUsage.id_product_option_type_usage AS idTypeUsage,
            typeUsage.is_optional AS isOptional,
            typeTranslation.name AS label
            FROM spy_product_option_type_usage AS typeUsage
            INNER JOIN spy_product_option_type AS optionType
            ON (typeUsage.fk_product_option_type = optionType.id_product_option_type)
            INNER JOIN spy_product_option_type_translation AS typeTranslation
            ON (typeTranslation.fk_product_option_type = optionType.id_product_option_type)
            INNER JOIN spy_locale
            ON typeTranslation.fk_locale = spy_locale.id_locale
            WHERE typeUsage.fk_product = :idProduct
            AND spy_locale.id_locale = :idLocale
            ORDER BY typeUsage.sequence'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute(
            [
                ':idProduct' => $idProduct,
                ':idLocale' => $idLocale,
            ]
        );

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $idTypeUsage
     * @param int $idLocale
     *
     * @return array
     */
    public function queryValueUsagesForTypeUsage($idTypeUsage, $idLocale)
    {
        $sql =
            'SELECT
            id_product_option_value_usage AS idValueUsage,
            valueUsage.sequence AS sequence,
            valueTranslation.name AS label,
            valuePrice.price AS price
            FROM spy_product_option_value_usage AS valueUsage
            INNER JOIN spy_product_option_value AS optionValue
            ON (valueUsage.fk_product_option_value = optionValue.id_product_option_value)
            INNER JOIN spy_product_option_value_translation AS valueTranslation
            ON (valueTranslation.fk_product_option_value = optionValue.id_product_option_value)
            INNER JOIN spy_locale ON valueTranslation.fk_locale = spy_locale.id_locale
            LEFT JOIN spy_product_option_value_price AS valuePrice
            ON (optionValue.fk_product_option_value_price = valuePrice.id_product_option_value_price)
            INNER JOIN spy_product_option_type_usage AS typeUsage
            ON (valueUsage.fk_product_option_type_usage = typeUsage.id_product_option_type_usage)
            WHERE typeUsage.id_product_option_type_usage = :idTypeUsage
            AND spy_locale.id_locale = :idLocale'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute(
            [
                ':idTypeUsage' => $idTypeUsage,
                ':idLocale' => $idLocale,
            ]
        );

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return array
     */
    public function queryTypeExclusionsForTypeUsage($idTypeUsage)
    {
        $sql =
            'SELECT fk_product_option_type_usage_b AS excludes
            FROM spy_product_option_type_usage_exclusion AS exclusionA
            WHERE exclusionA.fk_product_option_type_usage_a = :idTypeUsage
            UNION ALL
            SELECT fk_product_option_type_usage_a AS excludes
            FROM spy_product_option_type_usage_exclusion AS exclusionB
            WHERE exclusionB.fk_product_option_type_usage_b = :idTypeUsage'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute([':idTypeUsage' => $idTypeUsage]);

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param int $idValueUsage
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsage($idValueUsage)
    {
        $sql =
            'SELECT
            fk_product_option_value_usage_b AS valueUsageId,
            operator
            FROM spy_product_option_value_usage_constraint AS constraintA
            WHERE constraintA.fk_product_option_value_usage_a = :idValueUsage
            UNION ALL
            SELECT
            fk_product_option_value_usage_a AS valueUsageId,
            operator
            FROM spy_product_option_value_usage_constraint AS constraintB
            WHERE constraintB.fk_product_option_value_usage_b = :idValueUsage
            ORDER BY operator ASC'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute([':idValueUsage' => $idValueUsage]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $idValueUsage
     * @param string $operator
     *
     * @return array
     */
    public function queryValueConstraintsForValueUsageByOperator($idValueUsage, $operator)
    {
        $sql =
            'SELECT
            fk_product_option_value_usage_b AS valueUsageId
            FROM spy_product_option_value_usage_constraint AS constraintA
            WHERE constraintA.fk_product_option_value_usage_a = :idValueUsage
            AND operator = :operator
            UNION ALL
            SELECT
            fk_product_option_value_usage_a AS valueUsageId
            FROM spy_product_option_value_usage_constraint AS constraintB
            WHERE constraintB.fk_product_option_value_usage_b = :idValueUsage
            AND operator = :operator'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute(
            [
                ':idValueUsage' => $idValueUsage,
                ':operator' => $operator,
            ]);

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param int $idProduct
     *
     * @return array
     */
    public function queryConfigPresetsForConcreteProduct($idProduct)
    {
        $sql =
            'SELECT
            configPreset.id_product_option_configuration_preset AS presetId,
            is_default AS isDefault
            FROM
            spy_product_option_configuration_preset AS configPreset
            WHERE configPreset.fk_product = :idProduct
            ORDER BY configPreset.sequence'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute([':idProduct' => $idProduct]);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $idConfigPreset
     *
     * @return array
     */
    public function queryValueUsagesForConfigPreset($idConfigPreset)
    {
        $sql =
            'SELECT presetValue.fk_product_option_value_usage as valueUsageId
            FROM spy_product_option_configuration_preset_value AS presetValue
            WHERE presetValue.fk_product_option_configuration_preset = :idConfigPreset'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute([':idConfigPreset' => $idConfigPreset]);

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * @param int $idTypeUsage
     *
     * @return string|null
     */
    public function queryEffectiveTaxRateForTypeUsage($idTypeUsage)
    {
        $sql =
            'SELECT SUM(spy_tax_rate.rate) AS taxRate
            FROM spy_product_option_type_usage AS optionTypeUsage
            INNER JOIN spy_product_option_type AS optionType
            ON (optionTypeUsage.fk_product_option_type = optionType.id_product_option_type)
            INNER JOIN spy_tax_set
            ON (optionType.fk_tax_set=spy_tax_set.id_tax_set)
            INNER JOIN spy_tax_set_tax
            ON (spy_tax_set_tax.fk_tax_set=spy_tax_set.id_tax_set)
            INNER JOIN spy_tax_rate
            ON (spy_tax_set_tax.fk_tax_rate=spy_tax_rate.id_tax_rate)
            WHERE optionTypeUsage.id_product_option_type_usage = :idTypeUsage'
        ;

        $statement = $this->getConnection()
            ->prepare($sql);

        $statement->execute([':idTypeUsage' => $idTypeUsage]);

        return $statement->fetch(\PDO::FETCH_COLUMN);
    }

    /**
     * @return ConnectionInterface
     */
    private function getConnection()
    {
        return Propel::getConnection();
    }

}
