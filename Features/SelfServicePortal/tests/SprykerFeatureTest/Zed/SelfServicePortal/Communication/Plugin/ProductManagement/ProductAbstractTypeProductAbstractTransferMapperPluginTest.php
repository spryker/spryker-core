<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\ProductManagement;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\ProductManagement\ProductAbstractTypeProductAbstractTransferMapperPlugin;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductAbstractTypeForm;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group ProductManagement
 * @group ProductAbstractTypeProductAbstractTransferMapperPluginTest
 */
class ProductAbstractTypeProductAbstractTransferMapperPluginTest extends Unit
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
    public function testMapShouldMapProductAbstractTypesFromFormDataToTransfer(): void
    {
        // Arrange
        $productAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $productAbstractTransfer = new ProductAbstractTransfer();

        $formData = [
            ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES => new ArrayObject([$productAbstractTypeTransfer]),
        ];

        // Act
        $mappedProductAbstractTransfer = (new ProductAbstractTypeProductAbstractTransferMapperPlugin())
            ->map($formData, $productAbstractTransfer);

        // Assert
        $this->assertCount(1, $mappedProductAbstractTransfer->getProductAbstractTypes());
        $this->assertSame(
            $productAbstractTypeTransfer->getIdProductAbstractType(),
            $mappedProductAbstractTransfer->getProductAbstractTypes()[0]->getIdProductAbstractType(),
        );
    }

    /**
     * @return void
     */
    public function testMapShouldNotMapProductAbstractTypesWhenFormDataDoesNotContainProductAbstractTypes(): void
    {
        // Arrange
        $productAbstractTransfer = new ProductAbstractTransfer();
        $formData = [];

        // Act
        $mappedProductAbstractTransfer = (new ProductAbstractTypeProductAbstractTransferMapperPlugin())
            ->map($formData, $productAbstractTransfer);

        // Assert
        $this->assertEmpty($mappedProductAbstractTransfer->getProductAbstractTypes());
    }

    /**
     * @return void
     */
    public function testMapShouldMapMultipleProductAbstractTypesFromFormDataToTransfer(): void
    {
        // Arrange
        $firstProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $secondProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $productAbstractTransfer = new ProductAbstractTransfer();

        $formData = [
            ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES => new ArrayObject([
                $firstProductAbstractTypeTransfer,
                $secondProductAbstractTypeTransfer,
            ]),
        ];

        // Act
        $mappedProductAbstractTransfer = (new ProductAbstractTypeProductAbstractTransferMapperPlugin())
            ->map($formData, $productAbstractTransfer);

        // Assert
        $this->assertCount(2, $mappedProductAbstractTransfer->getProductAbstractTypes());

        $productAbstractTypeIds = [];
        foreach ($mappedProductAbstractTransfer->getProductAbstractTypes() as $productAbstractTypeTransfer) {
            $productAbstractTypeIds[] = $productAbstractTypeTransfer->getIdProductAbstractType();
        }

        $this->assertContains($firstProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
        $this->assertContains($secondProductAbstractTypeTransfer->getIdProductAbstractType(), $productAbstractTypeIds);
    }

    /**
     * @return void
     */
    public function testMapShouldOverrideExistingProductAbstractTypesInTransfer(): void
    {
        // Arrange
        $existingProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();
        $newProductAbstractTypeTransfer = $this->tester->haveProductAbstractType();

        $productAbstractTransfer = new ProductAbstractTransfer();
        $productAbstractTransfer->addProductAbstractType($existingProductAbstractTypeTransfer);

        $formData = [
            ProductAbstractTypeForm::FIELD_PRODUCT_ABSTRACT_TYPES => new ArrayObject([$newProductAbstractTypeTransfer]),
        ];

        // Act
        $mappedProductAbstractTransfer = (new ProductAbstractTypeProductAbstractTransferMapperPlugin())
            ->map($formData, $productAbstractTransfer);

        // Assert
        $this->assertCount(1, $mappedProductAbstractTransfer->getProductAbstractTypes());
        $this->assertSame(
            $newProductAbstractTypeTransfer->getIdProductAbstractType(),
            $mappedProductAbstractTransfer->getProductAbstractTypes()[0]->getIdProductAbstractType(),
        );
    }
}
