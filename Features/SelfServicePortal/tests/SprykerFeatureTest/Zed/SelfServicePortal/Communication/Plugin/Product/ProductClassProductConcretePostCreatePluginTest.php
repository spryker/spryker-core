<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Product;

use ArrayObject;
use Codeception\Test\Unit;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Product\ProductClassProductConcretePostCreatePlugin;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Product
 * @group ProductClassProductConcretePostCreatePluginTest
 */
class ProductClassProductConcretePostCreatePluginTest extends Unit
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
        $this->tester->setDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_STORAGE, $this->createMock(ProductStorageFacadeInterface::class));
    }

    /**
     * @return void
     */
    public function testCreateSavesProductClassesForProductConcrete(): void
    {
        // Arrange
        $productClassTransfer = $this->tester->haveProductClass();

        $productConcreteTransfer = $this->tester->haveFullProduct([
            'name' => 'Test Product',
        ]);

        $productClasses = new ArrayObject([$productClassTransfer]);
        $productConcreteTransfer->setProductClasses($productClasses);

        // Act

        $plugin = new ProductClassProductConcretePostCreatePlugin();
        $resultProductTransfer = $plugin->create($productConcreteTransfer);

        // Assert
        $this->assertNotNull($resultProductTransfer);
        $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $resultProductTransfer->getIdProductConcrete());

        $productClassNames = $this->tester->getProductClassNamesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());

        $this->assertNotEmpty($productClassNames, 'No product class relations were found in the database');
        $this->assertContains($productClassTransfer->getName(), $productClassNames, 'Expected product class relation was not found in the database');
    }

    /**
     * @return void
     */
    public function testCreateSavesMultipleProductClassesForProductConcrete(): void
    {
        // Arrange
        $productClass1Transfer = $this->tester->haveProductClass();
        $productClass2Transfer = $this->tester->haveProductClass();

        $productConcreteTransfer = $this->tester->haveFullProduct([
            'name' => 'Test Product Multiple Classes',
        ]);

        $productClasses = new ArrayObject([$productClass1Transfer, $productClass2Transfer]);
        $productConcreteTransfer->setProductClasses($productClasses);

        // Act
        $plugin = new ProductClassProductConcretePostCreatePlugin();
        $resultProductTransfer = $plugin->create($productConcreteTransfer);

        // Assert
        $this->assertNotNull($resultProductTransfer);

        $productClassNames = $this->tester->getProductClassNamesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());

        $this->assertCount(2, $productClassNames, 'Expected 2 product class relations in the database');

        $this->assertContains($productClass1Transfer->getName(), $productClassNames, 'First product class relation was not found in the database');
        $this->assertContains($productClass2Transfer->getName(), $productClassNames, 'Second product class relation was not found in the database');
    }

    /**
     * @return void
     */
    public function testCreateHandlesProductConcreteWithNoProductClasses(): void
    {
        // Arrange
        $productConcreteTransfer = $this->tester->haveFullProduct([
            'name' => 'Product Without Classes',
        ]);

        // Act
        $plugin = new ProductClassProductConcretePostCreatePlugin();
        $resultProductTransfer = $plugin->create($productConcreteTransfer);

        // Assert
        $this->assertNotNull($resultProductTransfer);
        $this->assertEquals($productConcreteTransfer->getIdProductConcrete(), $resultProductTransfer->getIdProductConcrete());

        $productClassNames = $this->tester->getProductClassNamesByIdProductConcrete($productConcreteTransfer->getIdProductConcrete());
        $this->assertEmpty($productClassNames, 'Expected no product class relations for product without classes');
    }
}
