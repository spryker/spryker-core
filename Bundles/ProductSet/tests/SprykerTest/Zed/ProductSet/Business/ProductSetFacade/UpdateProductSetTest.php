<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSet\Business\ProductSetFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ProductImageSetBuilder;
use Generated\Shared\DataBuilder\ProductSetBuilder;
use Generated\Shared\DataBuilder\ProductSetDataBuilder;
use Generated\Shared\Transfer\ProductSetTransfer;
use Propel\Runtime\Propel;
use Spryker\Shared\ProductSet\ProductSetConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSet
 * @group Business
 * @group ProductSetFacade
 * @group UpdateProductSetTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ProductSet\ProductSetBusinessTester $tester
 */
class UpdateProductSetTest extends Unit
{
    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        Propel::disableInstancePooling();
    }

    /**
     * @return void
     */
    public function testUpdateProductSetAbstractProductsPersistChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();

        $productSetTransfer = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::IS_ACTIVE => true,
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]);

        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Act
        $productSetTransfer
            ->setWeight(10)
            ->setIdProductAbstracts([
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer3->getIdProductAbstract(),
            ]);
        $productSetTransfer = $this->tester->getFacade()->updateProductSet($productSetTransfer);

        // Assert
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertSame(10, $actualProductSetTransfer->getWeight(), 'ProductSet should have expected weight.');
        $this->assertCount(2, $actualProductSetTransfer->getIdProductAbstracts(), 'ProductSet should have expected number of products.');

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testUpdateProductSetDataPersistChangesToDatabase()
    {
        // Arrange
        $productSetTransfer = $this->tester->generateProductSetTransfer();
        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        $updatedProductSetDataTransfer = (new ProductSetDataBuilder())->build();
        $productSetTransfer->getLocalizedData()[0]->setProductSetData($updatedProductSetDataTransfer);

        // Act
        $productSetTransfer = $this->tester->getFacade()->updateProductSet($productSetTransfer);

        // Assert
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertEquals(
            $updatedProductSetDataTransfer->toArray(),
            $actualProductSetTransfer->getLocalizedData()[0]->getProductSetData()->toArray(),
            'ProductSet should have expected data.'
        );

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testUpdateProductSetUrlPersistChangesToDatabase()
    {
        // Arrange
        $productSetTransfer = $this->tester->generateProductSetTransfer();
        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        $productSetTransfer->getLocalizedData()[0]->setUrl('/updated/product/set/url');

        // Act
        $productSetTransfer = $this->tester->getFacade()->updateProductSet($productSetTransfer);

        // Assert
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertSame(
            '/updated/product/set/url',
            $actualProductSetTransfer->getLocalizedData()[0]->getUrl(),
            'ProductSet should have expected URL.'
        );

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testUpdateProductSetImagesPersistChangesToDatabase()
    {
        // Arrange
        $productSetTransfer = $this->tester->generateProductSetTransfer();
        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        $productSetTransfer->getImageSets()[0]->setName('updated-image-set-name');
        $productSetTransfer->getImageSets()[0]->getProductImages()[0]->setExternalUrlSmall('/updated-image-url');
        $newImageSet = (new ProductImageSetBuilder())
            ->withProductImage()
            ->build();
        $productSetTransfer->setImageSets(new ArrayObject([$newImageSet]));

        // Act
        $productSetTransfer = $this->tester->getFacade()->updateProductSet($productSetTransfer);

        // Assert
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertCount(1, $actualProductSetTransfer->getImageSets(), 'ProductSet should have expected number of ProductImageSets.');
        $this->assertEquals(
            $productSetTransfer->getImageSets()[0]->toArray(),
            $actualProductSetTransfer->getImageSets()[0]->toArray(),
            'Existing ImageSet should have expected data.'
        );

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testAddProductsToSetPersistsChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();

        $productSetTransfer = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::IS_ACTIVE => true,
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]);
        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Act
        $productSetTransfer->setIdProductAbstracts([
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer3->getIdProductAbstract(),
        ]);
        $productSetTransfer = $this->tester->getFacade()->extendProductSet($productSetTransfer);

        // Assert
        $this->assertCount(3, $productSetTransfer->getIdProductAbstracts(), 'Returned ProductSet should have expected number of products.');

        $this->assertSame($productAbstractTransfer1->getIdProductAbstract(), $productSetTransfer->getIdProductAbstracts()[0], 'Product #1 should be in position 1.');
        $this->assertSame($productAbstractTransfer2->getIdProductAbstract(), $productSetTransfer->getIdProductAbstracts()[1], 'Product #2 should be in position 2.');
        $this->assertSame($productAbstractTransfer3->getIdProductAbstract(), $productSetTransfer->getIdProductAbstracts()[2], 'Product #3 should be in position 3.');

        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertEquals($productSetTransfer->toArray(), $actualProductSetTransfer->toArray(), 'Persisted ProductSet should match with returned values.');

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testRemoveProductsFromSetPersistsChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $productAbstractTransfer3 = $this->tester->haveProductAbstract();
        $productAbstractTransfer4 = $this->tester->haveProductAbstract();

        $productSetTransfer = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::IS_ACTIVE => true,
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
                $productAbstractTransfer3->getIdProductAbstract(),
                $productAbstractTransfer4->getIdProductAbstract(),
            ],
        ]);
        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        // Act
        $productSetTransfer->setIdProductAbstracts([
            $productAbstractTransfer2->getIdProductAbstract(),
            $productAbstractTransfer4->getIdProductAbstract(),
        ]);
        $productSetTransfer = $this->tester->getFacade()->removeFromProductSet($productSetTransfer);

        // Assert
        $this->assertCount(2, $productSetTransfer->getIdProductAbstracts(), 'Returned ProductSet should have expected number of products.');

        $this->assertSame($productAbstractTransfer1->getIdProductAbstract(), $productSetTransfer->getIdProductAbstracts()[0], 'Product #1 should be in position 1.');
        $this->assertSame($productAbstractTransfer3->getIdProductAbstract(), $productSetTransfer->getIdProductAbstracts()[1], 'Product #3 should be in position 2.');

        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertEquals($productSetTransfer->toArray(), $actualProductSetTransfer->toArray(), 'Persisted ProductSet should match with returned values.');

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }

    /**
     * @return void
     */
    public function testPartiallyUpdateProductSetPersistsOnlyRequestedChangesToDatabase()
    {
        // Arrange
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();

        $productSetTransfer = $this->tester->generateProductSetTransfer([
            ProductSetTransfer::IS_ACTIVE => false,
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $productAbstractTransfer1->getIdProductAbstract(),
                $productAbstractTransfer2->getIdProductAbstract(),
            ],
        ]);
        $productSetTransfer = $this->tester->getFacade()->createProductSet($productSetTransfer);

        $productSetTransferToUpdate = (new ProductSetBuilder([
            ProductSetTransfer::ID_PRODUCT_SET => $productSetTransfer->getIdProductSet(),
            ProductSetTransfer::IS_ACTIVE => true,
        ]))->build();

        // Act
        $this->tester->getFacade()->updateProductSet($productSetTransferToUpdate);

        // Assert
        $actualProductSetTransfer = $this->tester->getFacade()->findProductSet($productSetTransfer);
        $this->assertTrue($actualProductSetTransfer->getIsActive(), 'ProductSet should have been set to active.');
        $this->assertCount(2, $actualProductSetTransfer->getIdProductAbstracts(), 'Persisted ProductSet should have expected number of products assigned.');

        $this->assertCount(1, $actualProductSetTransfer->getImageSets(), 'ProductSet should have expected number of ProductImageSets.');

        $this->assertCount(1, $actualProductSetTransfer->getLocalizedData(), 'ProductSet should have expected number of localized data.');
        $this->assertSame(
            $productSetTransfer->getLocalizedData()[0]->getUrl(),
            $actualProductSetTransfer->getLocalizedData()[0]->getUrl(),
            'ProductSet should have expected URL.'
        );

        $this->tester->assertTouchActive(ProductSetConfig::RESOURCE_TYPE_PRODUCT_SET, $productSetTransfer->getIdProductSet(), 'ProductSet should have been touched as active.');
    }
}
