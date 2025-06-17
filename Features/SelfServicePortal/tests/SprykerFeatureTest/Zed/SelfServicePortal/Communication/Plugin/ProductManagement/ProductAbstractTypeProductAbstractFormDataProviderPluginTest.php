<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement\ProductAbstractTypeProductAbstractFormDataProviderPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductAbstractTypeForm;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ProductAbstractTypeProductAbstractFormDataProviderPluginTest
 */
class ProductAbstractTypeProductAbstractFormDataProviderPluginTest extends Unit
{
    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->tester->ensureProductAbstractTypeTableIsEmpty();
    }

    /**
     * @return void
     */
    public function testExpandShouldAddProductAbstractTypesToFormDataWhenProductAbstractExists(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $productAbstractTransfer->addProductAbstractType($productAbstractTypeTransfer);

        $formData = [];

        // Act
        $expandedFormData = (new ProductAbstractTypeProductAbstractFormDataProviderPlugin())
            ->expand($formData, $productAbstractTransfer);

        // Assert
        $this->assertArrayHasKey(ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES, $expandedFormData);
        $this->assertCount(1, $expandedFormData[ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES]);
        $this->assertSame(
            $productAbstractTypeTransfer->getIdProductAbstractType(),
            $expandedFormData[ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES][0]->getIdProductAbstractType(),
        );
    }

    /**
     * @return void
     */
    public function testExpandShouldNotAddProductAbstractTypesToFormDataWhenProductAbstractDoesNotExist(): void
    {
        // Arrange
        $productAbstractTransfer = new ProductAbstractTransfer();
        $formData = [];

        // Act
        $expandedFormData = (new ProductAbstractTypeProductAbstractFormDataProviderPlugin())
            ->expand($formData, $productAbstractTransfer);

        // Assert
        $this->assertSame($formData, $expandedFormData);
        $this->assertArrayNotHasKey(ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES, $expandedFormData);
    }

    /**
     * @return void
     */
    public function testExpandShouldPreserveExistingFormData(): void
    {
        // Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $productAbstractTransfer->addProductAbstractType($productAbstractTypeTransfer);

        $formData = [
            'existingField' => 'existingValue',
        ];

        // Act
        $expandedFormData = (new ProductAbstractTypeProductAbstractFormDataProviderPlugin())
            ->expand($formData, $productAbstractTransfer);

        // Assert
        $this->assertArrayHasKey('existingField', $expandedFormData);
        $this->assertSame('existingValue', $expandedFormData['existingField']);
        $this->assertArrayHasKey(ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES, $expandedFormData);
    }
}
