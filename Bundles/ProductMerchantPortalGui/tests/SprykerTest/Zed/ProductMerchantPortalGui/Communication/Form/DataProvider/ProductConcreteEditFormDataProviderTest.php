<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductMerchantPortalGui
 * @group Communication
 * @group Form
 * @group DataProvider
 * @group ProductConcreteEditFormDataProviderTest
 * Add your own group annotations below this line
 */
class ProductConcreteEditFormDataProviderTest extends Unit
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductConcreteEditForm::FIELD_USE_ABSTRACT_PRODUCT_PRICES
     *
     * @var string
     */
    protected const PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES = 'useAbstractProductPrices';

    /**
     * @var \SprykerTest\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetDataWillSetUseAbstractProductPricesToFalseWhenProductHasDefaultAndMerchantPriceForConcreteProduct(): void
    {
        // Arrange
        $merchantProductFacadeMock = $this->tester->createMerchantProductFacadeMock(
            [
                'findProductConcrete' => function () {
                    return (new ProductConcreteTransfer())
                        ->setIdProductConcrete(1)
                        ->setFkProductAbstract(1)
                        ->setPrices((new ArrayObject([new PriceProductTransfer()])));
                },
            ],
        );
        $priceProductFacadeMock = $this->tester->createProductMerchantPortalGuiToPriceProductFacadeMock(
            [
                'findProductConcretePricesWithoutPriceExtraction' => function () {
                    return [new PriceProductTransfer()];
                },
            ],
        );
        $productConcreteEditFormDataProvider = $this->tester->createProductConcreteEditFormDataProvider(
            $merchantProductFacadeMock,
            $priceProductFacadeMock,
        );

        // Act
        $data = $productConcreteEditFormDataProvider->getData(1);

        // Assert
        $this->assertFalse(
            $data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES],
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES
                . ' should be false when product has default and merchant prices.',
        );
    }

    /**
     * @return void
     */
    public function testGetDataWillSetUseAbstractProductPricesToFalseWhenProductHasDefaultAndNoMerchantPriceForConcreteProduct(): void
    {
        // Arrange
        $merchantProductFacadeMock = $this->tester->createMerchantProductFacadeMock(
            [
                'findProductConcrete' => function () {
                    return (new ProductConcreteTransfer())
                        ->setIdProductConcrete(1)
                        ->setFkProductAbstract(1)
                        ->setPrices((new ArrayObject([new PriceProductTransfer()])));
                },
            ],
        );
        $priceProductFacadeMock = $this->tester->createProductMerchantPortalGuiToPriceProductFacadeMock(
            [
                'findProductConcretePricesWithoutPriceExtraction' => function () {
                    return [];
                },
            ],
        );
        $productConcreteEditFormDataProvider = $this->tester->createProductConcreteEditFormDataProvider(
            $merchantProductFacadeMock,
            $priceProductFacadeMock,
        );

        // Act
        $data = $productConcreteEditFormDataProvider->getData(1);

        // Assert
        $this->assertFalse(
            $data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES],
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES
                . ' should be false when product has default prices.',
        );
    }

    /**
     * @return void
     */
    public function testGetDataWillSetUseAbstractProductPricesToFalseWhenProductHasMerchantAndNoDefaultPriceForConcreteProduct(): void
    {
        // Arrange
        $merchantProductFacadeMock = $this->tester->createMerchantProductFacadeMock(
            [
                'findProductConcrete' => function () {
                    return (new ProductConcreteTransfer())
                        ->setIdProductConcrete(1)
                        ->setFkProductAbstract(1);
                },
            ],
        );
        $priceProductFacadeMock = $this->tester->createProductMerchantPortalGuiToPriceProductFacadeMock(
            [
                'findProductConcretePricesWithoutPriceExtraction' => function () {
                    return [new PriceProductTransfer()];
                },
            ],
        );
        $productConcreteEditFormDataProvider = $this->tester->createProductConcreteEditFormDataProvider(
            $merchantProductFacadeMock,
            $priceProductFacadeMock,
        );

        // Act
        $data = $productConcreteEditFormDataProvider->getData(1);

        // Assert
        $this->assertFalse(
            $data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES],
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES
                . ' should be false when product has merchant prices.',
        );
    }

    /**
     * @return void
     */
    public function testGetDataWillSetUseAbstractProductPricesToTrueWhenProductHasNoMerchantAndNoDefaultPriceForConcreteProduct(): void
    {
        // Arrange
        $merchantProductFacadeMock = $this->tester->createMerchantProductFacadeMock(
            [
                'findProductConcrete' => function () {
                    return (new ProductConcreteTransfer())
                        ->setIdProductConcrete(1)
                        ->setFkProductAbstract(1);
                },
            ],
        );
        $priceProductFacadeMock = $this->tester->createProductMerchantPortalGuiToPriceProductFacadeMock(
            [
                'findProductConcretePricesWithoutPriceExtraction' => function () {
                    return [];
                },
            ],
        );
        $productConcreteEditFormDataProvider = $this->tester->createProductConcreteEditFormDataProvider(
            $merchantProductFacadeMock,
            $priceProductFacadeMock,
        );

        // Act
        $data = $productConcreteEditFormDataProvider->getData(1);

        // Assert
        $this->assertTrue(
            $data[static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES],
            static::PRODUCT_CONCRETE_EDIT_FORM_FIELD_USE_ABSTRACT_PRODUCT_PRICES
                . ' should be true when product has no default and no merchant prices.',
        );
    }
}
