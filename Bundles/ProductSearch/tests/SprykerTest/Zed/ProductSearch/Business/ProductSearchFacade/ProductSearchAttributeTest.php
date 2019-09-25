<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Business\ProductSearchFacade;

use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSearch
 * @group Business
 * @group ProductSearchFacade
 * @group ProductSearchAttributeTest
 * Add your own group annotations below this line
 */
class ProductSearchAttributeTest extends AbstractProductSearchFacadeTest
{
    /**
     * @return void
     */
    public function testCreateProductSearchAttribute()
    {
        $productSearchAttributeTransfer = new ProductSearchAttributeTransfer();
        $productSearchAttributeTransfer
            ->setKey('createProductSearchAttribute')
            ->setFilterType('foo');

        $productSearchAttributeTransfer = $this->productSearchFacade
            ->createProductSearchAttribute($productSearchAttributeTransfer);

        $this->assertGreaterThan(0, $productSearchAttributeTransfer->getIdProductSearchAttribute(), 'Getting idProductSearchAttribute failed!');
        $this->assertGreaterThan(0, $productSearchAttributeTransfer->getPosition(), 'Getting position failed!');
    }

    /**
     * @return void
     */
    public function testUpdateProductSearchAttribute()
    {
        $productSearchAttributeEntity = $this->createProductSearchAttributeEntity(
            'updateProductSearchAttribute',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
        );

        $productSearchAttributeTransfer = new ProductSearchAttributeTransfer();
        $productSearchAttributeTransfer
            ->setIdProductSearchAttribute($productSearchAttributeEntity->getIdProductSearchAttribute())
            ->setKey('updateProductSearchAttribute')
            ->setFilterType('bar');

        $productSearchAttributeTransfer = $this->productSearchFacade
            ->updateProductSearchAttribute($productSearchAttributeTransfer);

        $this->assertSame('bar', $productSearchAttributeTransfer->getFilterType());
    }

    /**
     * @return void
     */
    public function testDeleteProductSearchAttribute()
    {
        $productSearchAttributeEntity = $this->createProductSearchAttributeEntity(
            'deleteProductSearchAttribute',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
        );

        $productSearchAttributeTransfer = new ProductSearchAttributeTransfer();
        $productSearchAttributeTransfer->setIdProductSearchAttribute($productSearchAttributeEntity->getIdProductSearchAttribute());

        $this->productSearchFacade->deleteProductSearchAttribute($productSearchAttributeTransfer);

        $count = SpyProductSearchAttributeQuery::create()
            ->findByIdProductSearchAttribute($productSearchAttributeTransfer->getIdProductSearchAttribute())
            ->count();

        $this->assertEquals(0, $count, 'Deletion failed!');
    }

    /**
     * @return void
     */
    public function testGetProductSearchAttribute()
    {
        $productSearchAttributeEntity = $this->createProductSearchAttributeEntity(
            'getProductSearchAttribute',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
        );

        $productSearchAttributeTransfer = $this->productSearchFacade
            ->getProductSearchAttribute($productSearchAttributeEntity->getIdProductSearchAttribute());

        $this->assertEquals($productSearchAttributeEntity->getFilterType(), $productSearchAttributeTransfer->getFilterType());
    }

    /**
     * @return void
     */
    public function testGetProductSearchAttributeList()
    {
        SpyProductSearchAttributeQuery::create()->deleteAll();

        $this->createProductSearchAttributeEntity(
            'getProductSearchAttributeList-1',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
        );
        $this->createProductSearchAttributeEntity(
            'getProductSearchAttributeList-2',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
        );
        $this->createProductSearchAttributeEntity(
            'getProductSearchAttributeList-3',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
        );

        $productSearchAttributeList = $this->productSearchFacade
            ->getProductSearchAttributeList();

        $this->assertCount(3, $productSearchAttributeList);
    }

    /**
     * @return void
     */
    public function testUpdateProductSearchAttributeOrder()
    {
        $entity1 = $this->createProductSearchAttributeEntity(
            'getProductSearchAttributeList-1',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
                ->setPosition(1)
        );
        $entity2 = $this->createProductSearchAttributeEntity(
            'getProductSearchAttributeList-2',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
                ->setPosition(2)
        );
        $entity3 = $this->createProductSearchAttributeEntity(
            'getProductSearchAttributeList-3',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
                ->setPosition(3)
        );

        $productSearchAttributeList = [
            (new ProductSearchAttributeTransfer())
                ->setIdProductSearchAttribute($entity1->getIdProductSearchAttribute())
                ->setPosition(2),
            (new ProductSearchAttributeTransfer())
                ->setIdProductSearchAttribute($entity2->getIdProductSearchAttribute())
                ->setPosition(3),
            (new ProductSearchAttributeTransfer())
                ->setIdProductSearchAttribute($entity3->getIdProductSearchAttribute())
                ->setPosition(1),
        ];

        $this->productSearchFacade->updateProductSearchAttributeOrder($productSearchAttributeList);

        $entity1->reload();
        $entity2->reload();
        $entity3->reload();

        $this->assertEquals(
            [2, 3, 1],
            [$entity1->getPosition(), $entity2->getPosition(), $entity3->getPosition()],
            'Product search attribute reorder failed!'
        );
    }

    /**
     * @return array
     */
    public function touchProductAbstractByAsynchronousAttributesDataProvider()
    {
        return [
            'product abstract has attribute' => [
                ['touchProductAbstractByAsynchronousAttributes' => 'foo'], [], [], [],
            ],
            'localized product abstract has attribute' => [
                [], [], ['touchProductAbstractByAsynchronousAttributes' => 'foo'], [],
            ],
            'product concrete has attribute' => [
                [], ['touchProductAbstractByAsynchronousAttributes' => 'foo'], [], [],
            ],
            'localized product concrete has attribute' => [
                [], [], [], ['touchProductAbstractByAsynchronousAttributes' => 'foo'],
            ],
        ];
    }

    /**
     * @dataProvider touchProductAbstractByAsynchronousAttributesDataProvider
     *
     * @param array $abstractAttrs
     * @param array $abstractLocalizedAttrs
     * @param array $concreteAttrs
     * @param array $concreteLocalizedAttrs
     *
     * @return void
     */
    public function testTouchProductAbstractByAsynchronousAttributesOnCreate(
        array $abstractAttrs,
        array $abstractLocalizedAttrs,
        array $concreteAttrs,
        array $concreteLocalizedAttrs
    ) {
        $productAbstractEntity = $this->createProduct($abstractAttrs, $abstractLocalizedAttrs, $concreteAttrs, $concreteLocalizedAttrs);

        $productSearchAttributeEntity = $this->createProductSearchAttributeEntity(
            'touchProductAbstractByAsynchronousAttributes',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo')
        );
        $this->assertFalse($productSearchAttributeEntity->getSynced(), 'Product search attribute is marked as synced too early!');

        $this->productSearchFacade->touchProductAbstractByAsynchronousAttributes();

        $touchCount = SpyTouchQuery::create()
            ->filterByItemId($productAbstractEntity->getIdProductAbstract())
            ->filterByItemType('product_abstract')
            ->count();
        $this->assertEquals(1, $touchCount, 'Failed to touch abstract product!');

        $productSearchAttributeEntity->reload();
        $this->assertTrue($productSearchAttributeEntity->getSynced(), 'Product search attribute is not marked as synced!');
    }

    /**
     * @dataProvider touchProductAbstractByAsynchronousAttributesDataProvider
     *
     * @param array $abstractAttrs
     * @param array $abstractLocalizedAttrs
     * @param array $concreteAttrs
     * @param array $concreteLocalizedAttrs
     *
     * @return void
     */
    public function testTouchProductAbstractByAsynchronousAttributesOnUpdate(
        array $abstractAttrs,
        array $abstractLocalizedAttrs,
        array $concreteAttrs,
        array $concreteLocalizedAttrs
    ) {
        $productAbstractEntity = $this->createProduct($abstractAttrs, $abstractLocalizedAttrs, $concreteAttrs, $concreteLocalizedAttrs);

        $productSearchAttributeEntity = $this->createProductSearchAttributeEntity(
            'touchProductAbstractByAsynchronousAttributes',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo'),
            true
        );
        $this->assertTrue($productSearchAttributeEntity->getSynced(), 'Product search attribute is not marked as synced!');

        $productSearchAttributeEntity
            ->setFilterType('bar')
            ->save();
        $this->assertFalse($productSearchAttributeEntity->getSynced(), 'Product search attribute is not marked as async!');

        $this->productSearchFacade->touchProductAbstractByAsynchronousAttributes();

        $touchCount = SpyTouchQuery::create()
            ->filterByItemId($productAbstractEntity->getIdProductAbstract())
            ->filterByItemType('product_abstract')
            ->count();
        $this->assertEquals(1, $touchCount, 'Failed to touch abstract product!');

        $productSearchAttributeEntity->reload();
        $this->assertTrue($productSearchAttributeEntity->getSynced(), 'Product search attribute is not marked as synced!');
    }

    /**
     * @dataProvider touchProductAbstractByAsynchronousAttributesDataProvider
     *
     * @param array $abstractAttrs
     * @param array $abstractLocalizedAttrs
     * @param array $concreteAttrs
     * @param array $concreteLocalizedAttrs
     *
     * @return void
     */
    public function testTouchProductAbstractByAsynchronousAttributesOnDelete(
        array $abstractAttrs,
        array $abstractLocalizedAttrs,
        array $concreteAttrs,
        array $concreteLocalizedAttrs
    ) {
        $productAbstractEntity = $this->createProduct($abstractAttrs, $abstractLocalizedAttrs, $concreteAttrs, $concreteLocalizedAttrs);

        $productSearchAttributeEntity = $this->createProductSearchAttributeEntity(
            'touchProductAbstractByAsynchronousAttributes',
            (new ProductSearchAttributeTransfer())
                ->setFilterType('foo'),
            true
        );
        $this->assertTrue($productSearchAttributeEntity->getSynced(), 'Product search attribute is not marked as synced!');

        $productSearchAttributeEntity->delete();

        $this->productSearchFacade->touchProductAbstractByAsynchronousAttributes();

        $touchCount = SpyTouchQuery::create()
            ->filterByItemId($productAbstractEntity->getIdProductAbstract())
            ->filterByItemType('product_abstract')
            ->count();
        $this->assertEquals(1, $touchCount, 'Failed to touch abstract product!');
    }

    /**
     * @param string $attributeKey
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     * @param bool $synced
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute
     */
    protected function createProductSearchAttributeEntity($attributeKey, ProductSearchAttributeTransfer $productSearchAttributeTransfer, $synced = false)
    {
        $productAttributeKeyEntity = $this->createProductAttributeKeyEntity($attributeKey);
        $productSearchAttributeEntity = new SpyProductSearchAttribute();
        $productSearchAttributeEntity->fromArray($productSearchAttributeTransfer->toArray());
        $productSearchAttributeEntity
            ->setFkProductAttributeKey($productAttributeKeyEntity->getIdProductAttributeKey())
            ->setSynced($synced)
            ->save();

        return $productSearchAttributeEntity;
    }
}
