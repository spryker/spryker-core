<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductClassTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement\ProductClassProductConcreteFormEditDataProviderExpanderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductClassForm;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ProductClassProductConcreteFormEditDataProviderExpanderPluginTest
 */
class ProductClassProductConcreteFormEditDataProviderExpanderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testExpandShouldMapProductClassesToFormData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productClassTransfer = $this->tester->haveProductClass();
        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $productClassTransfer->getIdProductClassOrFail(),
        );

        $productConcreteTransfer->addProductClass($productClassTransfer);
        $formData = [];

        // Act
        $productClassProductConcreteFormEditDataProviderExpanderPlugin = new ProductClassProductConcreteFormEditDataProviderExpanderPlugin();
        $productClassProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey(ProductClassForm::FIELD_PRODUCT_CLASSES, $formData);
        $this->assertNotEmpty($formData[ProductClassForm::FIELD_PRODUCT_CLASSES]);
        $this->assertSame(
            $productConcreteTransfer->getProductClasses(),
            $formData[ProductClassForm::FIELD_PRODUCT_CLASSES],
        );
    }

    public function testExpandShouldNotMapProductClassesWhenProductHasNoId(): void
    {
        // Arrange
        $productConcreteTransfer = new ProductConcreteTransfer();
        $productClassTransfer = $this->tester->haveProductClass();
        $productConcreteTransfer->addProductClass($productClassTransfer);
        $formData = [];

        // Act
        $productClassProductConcreteFormEditDataProviderExpanderPlugin = new ProductClassProductConcreteFormEditDataProviderExpanderPlugin();
        $productClassProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertEmpty($formData);
    }

    public function testExpandShouldPreserveExistingFormData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $productClassTransfer = $this->tester->haveProductClass();
        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $productClassTransfer->getIdProductClassOrFail(),
        );

        $productConcreteTransfer->addProductClass($productClassTransfer);
        $formData = ['existingKey' => 'existingValue'];

        // Act
        $productClassProductConcreteFormEditDataProviderExpanderPlugin = new ProductClassProductConcreteFormEditDataProviderExpanderPlugin();
        $productClassProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey('existingKey', $formData);
        $this->assertSame('existingValue', $formData['existingKey']);
        $this->assertArrayHasKey(ProductClassForm::FIELD_PRODUCT_CLASSES, $formData);
    }

    public function testExpandShouldMapMultipleProductClassesToFormData(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveProduct();
        $firstProductClassTransfer = $this->tester->haveProductClass();
        $secondProductClassTransfer = $this->tester->haveProductClass();

        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $firstProductClassTransfer->getIdProductClassOrFail(),
        );
        $this->tester->haveProductToProductClass(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
            $secondProductClassTransfer->getIdProductClassOrFail(),
        );

        $productConcreteTransfer->addProductClass($firstProductClassTransfer);
        $productConcreteTransfer->addProductClass($secondProductClassTransfer);
        $formData = [];

        // Act
        $productClassProductConcreteFormEditDataProviderExpanderPlugin = new ProductClassProductConcreteFormEditDataProviderExpanderPlugin();
        $productClassProductConcreteFormEditDataProviderExpanderPlugin->expand($productConcreteTransfer, $formData);

        // Assert
        $this->assertArrayHasKey(ProductClassForm::FIELD_PRODUCT_CLASSES, $formData);
        $this->assertCount(2, $formData[ProductClassForm::FIELD_PRODUCT_CLASSES]);
        $this->assertContainsOnlyInstancesOf(ProductClassTransfer::class, $formData[ProductClassForm::FIELD_PRODUCT_CLASSES]);
    }
}
