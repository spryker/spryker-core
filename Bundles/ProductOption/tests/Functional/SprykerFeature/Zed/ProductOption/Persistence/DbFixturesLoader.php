<?php

namespace Functional\SprykerFeature\Zed\ProductOption\Persistence;

use Propel\Runtime\Propel;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
class DbFixturesLoader
{

    /**
     * @return array
     */
    public static function loadFixtures()
    {
        $ids = [];
        $dbConnection = Propel::getConnection();

        $dbConnection
            ->prepare("INSERT INTO spy_tax_rate (name, rate) VALUES ('Foo', 10)")
            ->execute();
        $ids['idTaxRate1'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_tax_rate (name, rate) VALUES ('Bar', 5)")
            ->execute();
        $ids['idTaxRate2'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_tax_set (name) VALUES ('Baz')")
            ->execute();
        $ids['idTaxSet'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_tax_set_tax (fk_tax_set, fk_tax_rate) VALUES ({$ids['idTaxSet']}, {$ids['idTaxRate1']}), ({$ids['idTaxSet']}, {$ids['idTaxRate2']})")
            ->execute();

        $dbConnection
            ->prepare("INSERT INTO spy_abstract_product (sku, fk_tax_set, attributes) VALUES ('ABC123', {$ids['idTaxSet']}, '{}')")
            ->execute();
        $ids['idAbstractProduct'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product (sku, fk_abstract_product, attributes) VALUES ('DEF456', {$ids['idAbstractProduct']}, '{}')")
            ->execute();
        $ids['idConcreteProduct'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare('INSERT INTO spy_product_option_type (fk_tax_set) VALUES (NULL)')
            ->execute();
        $ids['idTypeColor'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_type (fk_tax_set) VALUES ({$ids['idTaxSet']})")
            ->execute();
        $ids['idTypeSize'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type) VALUES ({$ids['idTypeColor']})")
            ->execute();
        $ids['idValueRed'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type) VALUES ({$ids['idTypeColor']})")
            ->execute();
        $ids['idValueBlue'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type) VALUES ({$ids['idTypeColor']})")
            ->execute();
        $ids['idValueGreen'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type) VALUES ({$ids['idTypeColor']})")
            ->execute();
        $ids['idValueYellow'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare('INSERT INTO spy_product_option_value_price (price) VALUES (199)')
            ->execute();
        $ids['idPriceLarge'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type, fk_product_option_value_price) VALUES ({$ids['idTypeSize']}, {$ids['idPriceLarge']})")
            ->execute();
        $ids['idValueLarge'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type) VALUES ({$ids['idTypeSize']})")
            ->execute();
        $ids['idValueSmall'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type) VALUES ({$ids['idTypeSize']})")
            ->execute();
        $ids['idValueMedium'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value (fk_product_option_type) VALUES ({$ids['idTypeSize']})")
            ->execute();
        $ids['idValueXSmall'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_locale (locale_name) VALUES ('xx_XX')")
            ->execute();
        $ids['idLocale'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_type_translation (name, fk_locale, fk_product_option_type)
                 VALUES
                     ('Size', {$ids['idLocale']}, {$ids['idTypeSize']}),
                     ('Color', {$ids['idLocale']}, {$ids['idTypeColor']})"
            )
            ->execute();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_translation (name, fk_locale, fk_product_option_value)
                 VALUES
                     ('Blue', {$ids['idLocale']}, {$ids['idValueBlue']}),
                     ('Red', {$ids['idLocale']}, {$ids['idValueRed']}),
                     ('Yellow', {$ids['idLocale']}, {$ids['idValueYellow']}),
                     ('Green', {$ids['idLocale']}, {$ids['idValueGreen']}),
                     ('Large', {$ids['idLocale']}, {$ids['idValueLarge']}),
                     ('Medium', {$ids['idLocale']}, {$ids['idValueMedium']}),
                     ('Small', {$ids['idLocale']}, {$ids['idValueSmall']}),
                     ('Extra Small', {$ids['idLocale']}, {$ids['idValueXSmall']})"
            )
            ->execute();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_type_usage (is_optional, sequence, fk_product, fk_product_option_type)
                 VALUES (0, 1, {$ids['idConcreteProduct']}, {$ids['idTypeColor']})"
            )
            ->execute();
        $ids['idUsageColor'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_type_usage (is_optional, sequence, fk_product, fk_product_option_type)
                 VALUES (0, 1, {$ids['idConcreteProduct']}, {$ids['idTypeSize']})"
            )
            ->execute();
        $ids['idUsageSize'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (1, {$ids['idValueBlue']}, {$ids['idUsageColor']})"
            )
            ->execute();
        $ids['idUsageBlue'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (2, {$ids['idValueRed']}, {$ids['idUsageColor']})"
            )
            ->execute();
        $ids['idUsageRed'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (3, {$ids['idValueYellow']}, {$ids['idUsageColor']})"
            )
            ->execute();
        $ids['idUsageYellow'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (4, {$ids['idValueGreen']}, {$ids['idUsageColor']})"
            )
            ->execute();
        $ids['idUsageGreen'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (1, {$ids['idValueLarge']}, {$ids['idUsageSize']})"
            )
            ->execute();
        $ids['idUsageLarge'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (2, {$ids['idValueMedium']}, {$ids['idUsageSize']})"
            )
            ->execute();
        $ids['idUsageMedium'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (3, {$ids['idValueSmall']}, {$ids['idUsageSize']})"
            )
            ->execute();
        $ids['idUsageSmall'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare(
                "INSERT INTO spy_product_option_value_usage (sequence, fk_product_option_value, fk_product_option_type_usage)
                 VALUES (4, {$ids['idValueXSmall']}, {$ids['idUsageSize']})"
            )
            ->execute();
        $ids['idUsageXSmall'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_type_usage_exclusion (fk_product_option_type_usage_a, fk_product_option_type_usage_b)
                       VALUES ({$ids['idUsageColor']}, {$ids['idUsageSize']})")
            ->execute();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_value_usage_constraint (fk_product_option_value_usage_a, fk_product_option_value_usage_b, operator)
                       VALUES
                           ({$ids['idUsageBlue']}, {$ids['idUsageSmall']}, 'NOT'),
                           ({$ids['idUsageRed']}, {$ids['idUsageMedium']}, 'ALWAYS'),
                           ({$ids['idUsageGreen']}, {$ids['idUsageSmall']}, 'ALLOW'),
                           ({$ids['idUsageGreen']}, {$ids['idUsageLarge']}, 'ALLOW')"
            )
            ->execute();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_configuration_preset (is_default, sequence, fk_product) VALUES (1, 1, {$ids['idConcreteProduct']})")
            ->execute();
        $ids['idConfigPresetA'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_configuration_preset (is_default, sequence, fk_product) VALUES (0, 2, {$ids['idConcreteProduct']})")
            ->execute();
        $ids['idConfigPresetB'] = $dbConnection->lastInsertId();

        $dbConnection
            ->prepare("INSERT INTO spy_product_option_configuration_preset_value (fk_product_option_configuration_preset, fk_product_option_value_usage)
                       VALUES
                           ({$ids['idConfigPresetA']}, {$ids['idUsageRed']}),
                           ({$ids['idConfigPresetA']}, {$ids['idUsageMedium']}),
                           ({$ids['idConfigPresetB']}, {$ids['idUsageGreen']}),
                           ({$ids['idConfigPresetB']}, {$ids['idUsageLarge']})"
            )
            ->execute();

        return $ids;
    }

}
