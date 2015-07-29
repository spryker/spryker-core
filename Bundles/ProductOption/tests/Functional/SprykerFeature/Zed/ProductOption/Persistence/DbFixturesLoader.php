<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\ProductOption\Persistence;

use Propel\Runtime\Propel;
use SprykerEngine\Zed\Locale\Persistence\Propel\SpyLocale;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionConfigurationPreset;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionConfigurationPresetValue;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionType;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeTranslation;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsage;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionTypeUsageExclusion;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValue;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValuePrice;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueTranslation;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsage;
use SprykerFeature\Zed\ProductOption\Persistence\Propel\SpyProductOptionValueUsageConstraint;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRate;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSetTax;

class DbFixturesLoader
{

    /**
     * @return array
     */
    public static function loadFixtures()
    {
        $ids = [];
        $dbConnection = Propel::getConnection();

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setRate(10)
            ->setName('Foo');
        $taxRateEntity->save();
        $ids['idTaxRate1'] = $taxRateEntity->getIdTaxRate();

        $taxRateEntity = new SpyTaxRate();
        $taxRateEntity->setRate(5)
            ->setName('Bar');
        $taxRateEntity->save();
        $ids['idTaxRate2'] = $taxRateEntity->getIdTaxRate();

        $taxSetEntity = new SpyTaxSet();
        $taxSetEntity->setName('Baz');
        $taxSetEntity->save();
        $ids['idTaxSet'] = $taxSetEntity->getIdTaxSet();

        $taxSetTaxEntity = new SpyTaxSetTax();
        $taxSetTaxEntity->setFkTaxSet($ids['idTaxSet'])
            ->setFkTaxRate($ids['idTaxRate1']);
        $taxSetTaxEntity->save();

        $taxSetTaxEntity = new SpyTaxSetTax();
        $taxSetTaxEntity->setFkTaxSet($ids['idTaxSet'])
            ->setFkTaxRate($ids['idTaxRate2']);
        $taxSetTaxEntity->save();


        $abstractProductEntity = new SpyAbstractProduct();
        $abstractProductEntity->setSku('ABC123')
            ->setFkTaxSet($ids['idTaxSet'])
            ->setAttributes('{}')
        ;
        $abstractProductEntity->save();
        $ids['idAbstractProduct'] = $abstractProductEntity->getIdAbstractProduct();

        $productEntity = new SpyProduct();
        $productEntity->setSku('DEF456')
            ->setFkAbstractProduct($ids['idAbstractProduct'])
            ->setAttributes('{}')
        ;
        $productEntity->save();
        $ids['idConcreteProduct'] = $productEntity->getIdProduct();

        $productOptionTypeEntity = new SpyProductOptionType();
        $productOptionTypeEntity->save();
        $ids['idTypeColor'] = $productOptionTypeEntity->getIdProductOptionType();

        $productOptionTypeEntity = new SpyProductOptionType();
        $productOptionTypeEntity->setFkTaxSet($ids['idTaxSet']);
        $productOptionTypeEntity->save();
        $ids['idTypeSize'] = $productOptionTypeEntity->getIdProductOptionType();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeColor']);
        $productOptionValueEntity->save();
        $ids['idValueRed'] = $productOptionValueEntity->getIdProductOptionValue();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeColor']);
        $productOptionValueEntity->save();
        $ids['idValueBlue'] = $productOptionValueEntity->getIdProductOptionValue();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeColor']);
        $productOptionValueEntity->save();
        $ids['idValueGreen'] = $productOptionValueEntity->getIdProductOptionValue();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeColor']);
        $productOptionValueEntity->save();
        $ids['idValueYellow'] = $productOptionValueEntity->getIdProductOptionValue();

        $productOptionValuePriceEntity = new SpyProductOptionValuePrice();
        $productOptionValuePriceEntity->setPrice(199);
        $productOptionValuePriceEntity->save();
        $ids['idPriceLarge'] = $productOptionValuePriceEntity->getIdProductOptionValuePrice();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeSize'])
            ->setFkProductOptionValuePrice($ids['idPriceLarge'])
        ;
        $productOptionValueEntity->save();
        $ids['idValueLarge'] = $productOptionValueEntity->getIdProductOptionValue();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeSize']);
        $productOptionValueEntity->save();
        $ids['idValueSmall'] = $productOptionValueEntity->getIdProductOptionValue();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeSize']);
        $productOptionValueEntity->save();
        $ids['idValueMedium'] = $productOptionValueEntity->getIdProductOptionValue();

        $productOptionValueEntity = new SpyProductOptionValue();
        $productOptionValueEntity->setFkProductOptionType($ids['idTypeSize']);
        $productOptionValueEntity->save();
        $ids['idValueXSmall'] = $productOptionValueEntity->getIdProductOptionValue();

        $localeEntity = new SpyLocale();
        $localeEntity->setLocaleName('xx_XX');
        $localeEntity->save();
        $ids['idLocale'] = $localeEntity->getIdLocale();

        $productOptionTypeTranslationEntity = new SpyProductOptionTypeTranslation();
        $productOptionTypeTranslationEntity->setName('Size')
            ->setFkLocale($ids['idLocale'])
            ->setFkProductOptionType($ids['idTypeSize'])
        ;
        $productOptionTypeTranslationEntity->save();

        $productOptionTypeTranslationEntity = new SpyProductOptionTypeTranslation();
        $productOptionTypeTranslationEntity->setName('Color')
            ->setFkLocale($ids['idLocale'])
            ->setFkProductOptionType($ids['idTypeColor'])
        ;
        $productOptionTypeTranslationEntity->save();

        $data = [
            'Blue' => $ids['idValueBlue'],
            'Red' => $ids['idValueRed'],
            'Yellow' => $ids['idValueYellow'],
            'Green' => $ids['idValueGreen'],
            'Large' => $ids['idValueLarge'],
            'Medium' => $ids['idValueMedium'],
            'Small' => $ids['idValueSmall'],
            'Extra' => $ids['idValueXSmall'],
        ];

        foreach ($data as $name => $fkProductOptionValue) {
            $productOptionValueTranslationEntity = new SpyProductOptionValueTranslation();
            $productOptionValueTranslationEntity->setName($name)
                ->setFkLocale($ids['idLocale'])
                ->setFkProductOptionValue($fkProductOptionValue)
            ;
            $productOptionValueTranslationEntity->save();
        }

        $productOptionTypeUsageEntity = new SpyProductOptionTypeUsage();
        $productOptionTypeUsageEntity->setIsOptional(0)
            ->setSequence(1)
            ->setFkProduct($ids['idConcreteProduct'])
            ->setFkProductOptionType($ids['idTypeColor'])
        ;
        $productOptionTypeUsageEntity->save();
        $ids['idUsageColor'] = $productOptionTypeUsageEntity->getIdProductOptionTypeUsage();

        $productOptionTypeUsageEntity = new SpyProductOptionTypeUsage();
        $productOptionTypeUsageEntity->setIsOptional(0)
            ->setSequence(1)
            ->setFkProduct($ids['idConcreteProduct'])
            ->setFkProductOptionType($ids['idTypeSize'])
        ;
        $productOptionTypeUsageEntity->save();
        $ids['idUsageSize'] = $productOptionTypeUsageEntity->getIdProductOptionTypeUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(1)
            ->setFkProductOptionValue($ids['idValueBlue'])
            ->setFkProductOptionTypeUsage($ids['idUsageColor'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageBlue'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(2)
            ->setFkProductOptionValue($ids['idValueRed'])
            ->setFkProductOptionTypeUsage($ids['idUsageColor'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageRed'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(3)
            ->setFkProductOptionValue($ids['idValueYellow'])
            ->setFkProductOptionTypeUsage($ids['idUsageColor'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageYellow'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(4)
            ->setFkProductOptionValue($ids['idValueGreen'])
            ->setFkProductOptionTypeUsage($ids['idUsageColor'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageGreen'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(1)
            ->setFkProductOptionValue($ids['idValueLarge'])
            ->setFkProductOptionTypeUsage($ids['idUsageSize'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageLarge'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(2)
            ->setFkProductOptionValue($ids['idValueMedium'])
            ->setFkProductOptionTypeUsage($ids['idUsageSize'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageMedium'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(3)
            ->setFkProductOptionValue($ids['idValueSmall'])
            ->setFkProductOptionTypeUsage($ids['idUsageSize'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageSmall'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionValueUsageEntity = new SpyProductOptionValueUsage();
        $productOptionValueUsageEntity->setSequence(4)
            ->setFkProductOptionValue($ids['idValueXSmall'])
            ->setFkProductOptionTypeUsage($ids['idUsageSize'])
        ;
        $productOptionValueUsageEntity->save();
        $ids['idUsageXSmall'] = $productOptionValueUsageEntity->getIdProductOptionValueUsage();

        $productOptionTypeUsageExclusionEntity = new SpyProductOptionTypeUsageExclusion();
        $productOptionTypeUsageExclusionEntity->setFkProductOptionTypeUsageA($ids['idUsageColor'])
            ->setFkProductOptionTypeUsageB($ids['idUsageSize'])
        ;
        $productOptionTypeUsageExclusionEntity->save();

        $productOptionValueUsageConstraintEntity = new SpyProductOptionValueUsageConstraint();
        $productOptionValueUsageConstraintEntity
            ->setFkProductOptionValueUsageA($ids['idUsageBlue'])
            ->setFkProductOptionValueUsageB($ids['idUsageSmall'])
            ->setOperator('NOT')
        ;
        $productOptionValueUsageConstraintEntity->save();

        $productOptionValueUsageConstraintEntity = new SpyProductOptionValueUsageConstraint();
        $productOptionValueUsageConstraintEntity
            ->setFkProductOptionValueUsageA($ids['idUsageRed'])
            ->setFkProductOptionValueUsageB($ids['idUsageMedium'])
            ->setOperator('ALWAYS')
        ;
        $productOptionValueUsageConstraintEntity->save();

        $productOptionValueUsageConstraintEntity = new SpyProductOptionValueUsageConstraint();
        $productOptionValueUsageConstraintEntity
            ->setFkProductOptionValueUsageA($ids['idUsageGreen'])
            ->setFkProductOptionValueUsageB($ids['idUsageSmall'])
            ->setOperator('ALLOW')
        ;

        $productOptionValueUsageConstraintEntity->save();

        $productOptionValueUsageConstraintEntity = new SpyProductOptionValueUsageConstraint();
        $productOptionValueUsageConstraintEntity
            ->setFkProductOptionValueUsageA($ids['idUsageGreen'])
            ->setFkProductOptionValueUsageB($ids['idUsageLarge'])
            ->setOperator('ALLOW')
        ;
        $productOptionValueUsageConstraintEntity->save();

        $productOptionConfigurationPresetEntity = new SpyProductOptionConfigurationPreset();
        $productOptionConfigurationPresetEntity->setIsDefault(true)
            ->setSequence(1)
            ->setFkProduct($ids['idConcreteProduct'])
        ;
        $productOptionConfigurationPresetEntity->save();
        $ids['idConfigPresetA'] = $productOptionConfigurationPresetEntity->getIdProductOptionConfigurationPreset();

        $productOptionConfigurationPresetEntity = new SpyProductOptionConfigurationPreset();
        $productOptionConfigurationPresetEntity->setIsDefault(false)
            ->setSequence(2)
            ->setFkProduct($ids['idConcreteProduct'])
        ;
        $productOptionConfigurationPresetEntity->save();
        $ids['idConfigPresetB'] = $productOptionConfigurationPresetEntity->getIdProductOptionConfigurationPreset();

        $data = [
            $ids['idConfigPresetA'] => $ids['idUsageRed'],
            $ids['idConfigPresetA'] => $ids['idUsageMedium'],
            $ids['idConfigPresetB'] => $ids['idUsageGreen'],
            $ids['idConfigPresetB'] => $ids['idUsageLarge']
        ];

        foreach ($data as $fkProductOptionConfigurationPreset => $fkProductOptionValueUsage) {
            $productOptionConfigurationPresetValueEntity = new SpyProductOptionConfigurationPresetValue();
            $productOptionConfigurationPresetValueEntity
                ->setFkProductOptionConfigurationPreset($fkProductOptionConfigurationPreset)
                ->setFkProductOptionValueUsage($fkProductOptionValueUsage)
            ;
            $productOptionConfigurationPresetValueEntity->save();
        }

        return $ids;
    }

}
