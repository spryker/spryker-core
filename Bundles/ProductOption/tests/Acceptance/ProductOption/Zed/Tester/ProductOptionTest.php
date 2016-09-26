<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Acceptance\ProductOption\Zed\Tester;

use ProductOption\ZedAcceptanceTester;

/**
 * @group Acceptance
 * @group ProductOption
 * @group Zed
 * @group Tester
 * @group ProductOptionTest
 */
class ProductOptionTest extends ZedAcceptanceTester
{

    const LANGUAGE_SWITCH_XPATH = '//*[@id="option-value-translations"]/div[2]/div/div[1]/a';


    /**
     * @param array $values
     *
     * @return void
     */
    public function fillOptionValues(array $values)
    {
        foreach ($values as $index => $value) {

            $elementNr = $index + 1;

            if ($index > 0) {
                $this->click('#add-another-option');
            }

            $this->fillField('#product_option_general_productOptionValues_' . $elementNr . '_value', $value['value_translation_key']);
            $this->fillField('#product_option_general_productOptionValues_' . $elementNr . '_sku', $value['value_sku'] . rand(1, 999));
            $this->fillField('#product_option_general_productOptionValues_' . $elementNr . '_price', $value['value_price']);
        }

        $numberOfTranslations = count($values) * 2;
        for ($i = 1; $i <= $numberOfTranslations; $i++) {
            $this->fillField('#product_option_general_productOptionValueTranslations_' . $i . '_name', 'Option value translation');
        }
    }

    /**
     * @param array $groupData
     *
     * @return void
     */
    public function fillOptionGroupData(array $groupData)
    {
        $this->fillField('#product_option_general_name', $groupData['group_name_translation_key']);
        $this->selectOption('#product_option_general_fkTaxSet', $groupData['fk_tax_set']);

        $this->fillField(
            '#product_option_general_groupNameTranslations_0_name',
            'Option value translation in first language'
        );

        $this->fillField(
            '#product_option_general_groupNameTranslations_1_name',
            'Option value translation in second language'
        );
    }

    /**
     * @return void
     */
    public function expandSecondTranslationBlock()
    {
        $this->click(self::LANGUAGE_SWITCH_XPATH);
    }


    /**
     * @return void
     */
    public function assignRandomProducts()
    {
        $this->click('//*[@id="page-wrapper"]/div[3]/div[2]/ul/li[2]/a');
        $this->click('//*[@id="all_products_checkbox_1"]');
        $this->click('//*[@id="all_products_checkbox_2"]');
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    public function unassignProduct($idProduct)
    {
        $this->click('#products-to-be-assigned');
        $this->click("//a[@data-id='" . $idProduct . "']");
    }

}
