<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AttributeMapStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractStorageTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use ReflectionProperty;
use Spryker\Zed\ProductStorage\Business\Expander\ProductAbstractStorageExpander;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductStorage
 * @group Business
 * @group ExpandWithAttributeVariantCollectionTest
 * Add your own group annotations below this line
 */
class ExpandWithAttributeVariantCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductStorage\ProductStorageBusinessTester
     */
    protected $tester;

    protected const FAKE_SKU_1 = 'fake-sku-1';
    protected const FAKE_SKU_2 = 'fake-sku-2';

    protected const FAKE_ATTRIBUTES_1 = [
        'attribute_1' => 'value_1_1',
        'attribute_2' => 'value_1_2',
    ];

    protected const FAKE_ATTRIBUTES_2 = [
        'attribute_1' => 'value_2_1',
        'attribute_2' => 'value_2_2',
    ];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->resetSuperAttributesCache();
    }

    /**
     * @return void
     */
    public function testExpandWithAttributeVariantCollection(): void
    {
        //Arrange
        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $firstProductConcreteTransfer = $this->tester->haveFullProduct(
            [ProductConcreteTransfer::ATTRIBUTES => static::FAKE_ATTRIBUTES_1],
            [ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract()]
        );
        $secondProductConcreteTransfer = $this->tester->haveFullProduct(
            [ProductConcreteTransfer::ATTRIBUTES => static::FAKE_ATTRIBUTES_2],
            [ProductAbstractTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstract()]
        );

        $productAbstractStorageTransfer = (new ProductAbstractStorageTransfer())
            ->setAttributeMap((new AttributeMapStorageTransfer())->setProductConcreteIds([
                $firstProductConcreteTransfer->getSku() => (string)$firstProductConcreteTransfer->getIdProductConcrete(),
                $secondProductConcreteTransfer->getSku() => (string)$secondProductConcreteTransfer->getIdProductConcrete(),
            ]));

        // Act
        $productAbstractStorageTransfer = $this->tester->getFacade()
            ->expandWithAttributeVariantCollection($productAbstractStorageTransfer);

        // Assert
        $this->assertSame(
            static::FAKE_ATTRIBUTES_1,
            $productAbstractStorageTransfer->getAttributeMap()->getAttributeVariantCollection()[$firstProductConcreteTransfer->getIdProductConcrete()]
        );
        $this->assertSame(
            static::FAKE_ATTRIBUTES_2,
            $productAbstractStorageTransfer->getAttributeMap()->getAttributeVariantCollection()[$secondProductConcreteTransfer->getIdProductConcrete()]
        );
    }

    /**
     * @return void
     */
    public function testExpandWithAttributeVariantCollectionWithoutAttributeMap(): void
    {
        //Arrange
        $productAbstractStorageTransfer = (new ProductAbstractStorageTransfer())
            ->setAttributeMap(null);

        // Act
        $productAbstractStorageTransfer = $this->tester->getFacade()
            ->expandWithAttributeVariantCollection($productAbstractStorageTransfer);

        // Assert
        $this->assertNull($productAbstractStorageTransfer->getAttributeMap());
    }

    /**
     * @return void
     */
    public function testExpandWithAttributeVariantCollectionWithoutProductConcreteIds(): void
    {
        //Arrange
        $productAbstractStorageTransfer = (new ProductAbstractStorageTransfer())
            ->setAttributeMap((new AttributeMapStorageTransfer())->setProductConcreteIds(null));

        // Act
        $productAbstractStorageTransfer = $this->tester->getFacade()
            ->expandWithAttributeVariantCollection($productAbstractStorageTransfer);

        // Assert
        $this->assertEmpty($productAbstractStorageTransfer->getAttributeMap()->getAttributeVariantCollection());
    }

    /**
     * @return void
     */
    public function testExpandWithAttributeVariantCollectionWithEmptyProductConcreteIds(): void
    {
        //Arrange
        $productAbstractStorageTransfer = (new ProductAbstractStorageTransfer())
            ->setAttributeMap((new AttributeMapStorageTransfer())->setProductConcreteIds([]));

        // Act
        $productAbstractStorageTransfer = $this->tester->getFacade()
            ->expandWithAttributeVariantCollection($productAbstractStorageTransfer);

        // Assert
        $this->assertEmpty($productAbstractStorageTransfer->getAttributeMap()->getAttributeVariantCollection());
    }

    /**
     * @return void
     */
    public function testExpandWithAttributeVariantCollectionWithFakeProductConcreteIds(): void
    {
        //Arrange
        $productAbstractStorageTransfer = (new ProductAbstractStorageTransfer())
            ->setAttributeMap((new AttributeMapStorageTransfer())->setProductConcreteIds([
                static::FAKE_SKU_1 => '123456',
                static::FAKE_SKU_2 => '654321',
            ]));

        // Act
        $productAbstractStorageTransfer = $this->tester->getFacade()
            ->expandWithAttributeVariantCollection($productAbstractStorageTransfer);

        // Assert
        $this->assertEmpty($productAbstractStorageTransfer->getAttributeMap()->getAttributeVariantCollection());
    }

    /**
     * @return void
     */
    protected function resetSuperAttributesCache(): void
    {
        $reflection = new ReflectionProperty(ProductAbstractStorageExpander::class, 'superAttributesCache');
        $reflection->setAccessible(true);
        $reflection->setValue(null, null);
    }
}
