<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOptionPresentationTester extends Actor
{
    use _generated\ProductOptionPresentationTesterActions;

    /**
     * @var string
     */
    public const LANGUAGE_SWITCH_XPATH = '//*[@id="option-value-translations"]/div[2]/div/div[1]/a';

    /**
     * @var array
     */
    protected $locales = ['en_US', 'de_DE'];

    /**
     * @param array $values
     *
     * @return void
     */
    public function fillOptionValues(array $values): void
    {
        foreach ($values as $index => $value) {
            $elementNr = $index + 1;

            if ($index > 0) {
                $this->click('#add-another-option');
            }

            $this->fillField('#product_option_general_productOptionValues_' . $elementNr . '_value', $value['value_translation_key'] . rand(1, 999));
            $this->fillField('#product_option_general_productOptionValues_' . $elementNr . '_sku', $value['value_sku'] . rand(1, 999));

            $currencyIndex = 0;
            foreach ($value['prices'] as $currencyPrices) {
                $this->fillField('#product_option_general_productOptionValues_' . $elementNr . '_prices_' . $currencyIndex . '_net_amount', $currencyPrices['value_net_amount']);
                $this->fillField('#product_option_general_productOptionValues_' . $elementNr . '_prices_' . $currencyIndex . '_gross_amount', $currencyPrices['value_gross_amount']);
                $currencyIndex++;
            }
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
    public function fillOptionGroupData(array $groupData): void
    {
        $this->fillField('#product_option_general_name', $groupData['group_name_translation_key'] . rand(1, 999));
        $this->selectOption('#product_option_general_fkTaxSet', $groupData['fk_tax_set']);

        $this->fillField(
            '#product_option_general_groupNameTranslations_0_name',
            'Option value translation in first language',
        );

        $this->fillField(
            '#product_option_general_groupNameTranslations_1_name',
            'Option value translation in second language',
        );
    }

    /**
     * @return void
     */
    public function expandSecondTranslationBlock(): void
    {
        $this->click(static::LANGUAGE_SWITCH_XPATH);
    }

    /**
     * @return void
     */
    public function assignProducts(): void
    {
        $this->selectProductTab();

        $this->waitForElementNotVisible('.dataTables_processing');

        $productIds = [
            $this->grabTextFrom('//*[@id="product-table"]/tbody/tr[1]/td[1]'),
            $this->grabTextFrom('//*[@id="product-table"]/tbody/tr[2]/td[1]'),
        ];

        foreach ($productIds as $id) {
            $this->click('//*[@id="all_products_checkbox_' . $id . '"]');
        }

        $this->waitForElementNotVisible('.dataTables_processing');
    }

    /**
     * @return void
     */
    public function unassignProduct(): void
    {
        $this->click('#products-to-be-assigned');

        $idProduct = $this->grabTextFrom('//*[@id="selectedProductsTable"]/tbody/tr[1]/td[1]');

        $this->click("//a[@data-id='" . $idProduct . "']");
    }

    /**
     * @return void
     */
    public function selectProductTab(): void
    {
        $this->waitAndClick('//*[@id="page-wrapper"]/div[3]/div[2]/ul/li[2]/a');
    }

    /**
     * @return void
     */
    public function submitProductGroupForm(): void
    {
        $this->waitAndClick('#create-product-option-button');
    }

    /**
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function createProductOptionGroupTransfer(): ProductOptionGroupTransfer
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->setName('group.name.translation.key.edit');
        $productOptionGroupTransfer->setFkTaxSet(1);

        $this->addGroupNameTranslations($productOptionGroupTransfer);

        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('option.value.translation.key.edit');
        $productOptionValueTransfer->setPrices(new ArrayObject(
            [
                (new MoneyValueTransfer())
                    ->setFkStore(1)
                    ->setFkCurrency(93)
                    ->setGrossAmount(1000)
                    ->setNetAmount(2000),
            ],
        ));
        $productOptionValueTransfer->setSku('testing_sky_' . rand(1, 999));
        $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

        $this->addOptionValueTranslations($productOptionValueTransfer, $productOptionGroupTransfer);

        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->setValue('option.value.translation.key.edit.second');
        $productOptionValueTransfer->setPrices(new ArrayObject(
            [
                (new MoneyValueTransfer())
                    ->setFkStore(1)
                    ->setFkCurrency(93)
                    ->setGrossAmount(3000)
                    ->setNetAmount(4000),
            ],
        ));
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
    protected function createTranslation(string $translationKey, string $localeIsoCode): ProductOptionTranslationTransfer
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
    ): void {
        foreach ($this->locales as $locale) {
            $productOptionTranslationTransfer = $this->createTranslation(
                $productOptionValueTransfer->getValue(),
                $locale,
            );
            $productOptionGroupTransfer->addProductOptionValueTranslation($productOptionTranslationTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    protected function addGroupNameTranslations(ProductOptionGroupTransfer $productOptionGroupTransfer): void
    {
        foreach ($this->locales as $locale) {
            $productOptionTranslationTransfer = $this->createTranslation($productOptionGroupTransfer->getName(), $locale);
            $productOptionGroupTransfer->addGroupNameTranslation($productOptionTranslationTransfer);
        }
    }

    /**
     * @param string $element
     *
     * @return void
     */
    public function waitAndClick(string $element): void
    {
        $this->waitForElement($element);
        $this->click($element);
    }
}
