<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Acceptance\ProductOption\Zed\Tester;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
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
     * @var array
     */
    protected $locales = ['en_US', 'de_DE'];

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
     * @param array $productIds
     *
     * @return array
     */
    public function assignProducts($productIds = [])
    {
        $this->selectProductTab();

        foreach ($productIds as $id) {
            $this->click('//*[@id="all_products_checkbox_' . $id . '"]');
        }
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

    /**
     * @return void
     */
    public function selectProductTab()
    {
        $this->click('//*[@id="page-wrapper"]/div[3]/div[2]/ul/li[2]/a');
    }

    /**
     * @return void
     */
    public function submitProductGroupForm()
    {
        $this->click('#create-product-option-button');
    }


    /**
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function createProductOptionGroupTransfer()
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('group.name.translation.key.edit');
        $productOptionGroupTransfer->setFkTaxSet(1);

        $this->addGroupNameTranslations($productOptionGroupTransfer);

        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('option.value.translation.key.edit');
        $productOptionValueTransfer->setPrice(1000);
        $productOptionValueTransfer->setSku('testing_sky_' . rand(1, 999));
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $this->addOptionValueTranslations($productOptionValueTransfer, $productOptionGroupTransfer);

        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('option.value.translation.key.edit.second');
        $productOptionValueTransfer->setPrice(2000);
        $productOptionValueTransfer->setSku('testing_sky_second' . rand(1, 999));
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $this->addOptionValueTranslations($productOptionValueTransfer, $productOptionGroupTransfer);

        $productOptionGroupTransfer->addProductsToBeAssigned([1, 2, 3]);

        return $productOptionGroupTransfer;
    }

    /**
     * @param string $translationKey
     * @param string $localeIsoCode
     *
     * @return \Generated\Shared\Transfer\ProductOptionTranslationTransfer
     */
    protected function createTranslation($translationKey, $localeIsoCode)
    {
        $productOptionTranslationTransfer = new ProductOptionTranslationTransfer();
        $productOptionTranslationTransfer->setKey($translationKey);
        $productOptionTranslationTransfer->setName('Translation1');
        $productOptionTranslationTransfer->setLocaleCode($localeIsoCode);

        return $productOptionTranslationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionValueTransfer $productOptionValueTransfer
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    protected function addOptionValueTranslations(
        ProductOptionValueTransfer $productOptionValueTransfer,
        ProductOptionGroupTransfer $productOptionGroupTransfer
    ) {
        foreach ($this->locales as $locale) {
            $productOptionTranslationTransfer = $this->createTranslation(
                $productOptionValueTransfer->getValue(),
                $locale
            );
            $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionTranslationTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    protected function addGroupNameTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer)
    {
        foreach ($this->locales as $locale) {
            $productOptionTranslationTransfer = $this->createTranslation($productOptionGroupTransfer->getName(), $locale);
            $productOptionGroupTransfer->addGroupNameTranslation($productOptionTranslationTransfer);
        }
    }

}
