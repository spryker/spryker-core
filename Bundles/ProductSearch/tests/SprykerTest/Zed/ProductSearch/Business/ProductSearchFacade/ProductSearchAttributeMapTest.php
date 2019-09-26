<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSearch\Business\ProductSearchFacade;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\ProductSearchPreferencesTransfer;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMap;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductSearch
 * @group Business
 * @group ProductSearchFacade
 * @group ProductSearchAttributeMapTest
 * Add your own group annotations below this line
 */
class ProductSearchAttributeMapTest extends AbstractProductSearchFacadeTest
{
    /**
     * @return void
     */
    public function testCreateProductSearchPreferences()
    {
        $productSearchPreferencesTransfer = new ProductSearchPreferencesTransfer();
        $productSearchPreferencesTransfer
            ->setKey('testCreateProductSearchPreferences')
            ->setFullText(true)
            ->setSuggestionTerms(true);

        $productSearchPreferencesTransfer = $this->productSearchFacade->createProductSearchPreferences($productSearchPreferencesTransfer);

        $count = SpyProductSearchAttributeMapQuery::create()
            ->filterByFkProductAttributeKey($productSearchPreferencesTransfer->getIdProductAttributeKey())
            ->count();

        $this->assertEquals(2, $count);
    }

    /**
     * @return void
     */
    public function testUpdateProductSearchPreferences()
    {
        $productSearchAttributeMapEntity = $this->createProductSearchAttributeMapEntity('updateProductSearchPreferences');

        $productSearchPreferencesTransfer = new ProductSearchPreferencesTransfer();
        $productSearchPreferencesTransfer
            ->setIdProductAttributeKey($productSearchAttributeMapEntity->getFkProductAttributeKey())
            ->setFullText(false)
            ->setFullTextBoosted(true)
            ->setSuggestionTerms(true);

        $this->productSearchFacade->updateProductSearchPreferences($productSearchPreferencesTransfer);

        $count = SpyProductSearchAttributeMapQuery::create()
            ->filterByFkProductAttributeKey($productSearchAttributeMapEntity->getFkProductAttributeKey())
            ->count();

        $this->assertEquals(2, $count);
    }

    /**
     * @return void
     */
    public function testCleanProductSearchPreferences()
    {
        $productSearchAttributeMapEntity = $this->createProductSearchAttributeMapEntity('cleanProductSearchPreferences');

        $productSearchPreferencesTransfer = new ProductSearchPreferencesTransfer();
        $productSearchPreferencesTransfer
            ->setIdProductAttributeKey($productSearchAttributeMapEntity->getFkProductAttributeKey());

        $this->productSearchFacade->cleanProductSearchPreferences($productSearchPreferencesTransfer);

        $count = SpyProductSearchAttributeMapQuery::create()
            ->filterByFkProductAttributeKey($productSearchAttributeMapEntity->getFkProductAttributeKey())
            ->count();

        $this->assertEquals(0, $count);
    }

    /**
     * @return void
     */
    public function testSuggestProductSearchAttributes()
    {
        // Arrange
        $key = 'suggestProductSearchAttributes';
        $this->createProductAttributeKeyEntity($key);

        // Act
        $suggestedAttributes = $this->productSearchFacade->suggestProductSearchAttributeKeys($key);

        // Assert
        $this->assertCount(1, $suggestedAttributes);
        $this->assertContains($key, $suggestedAttributes);
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
    public function testTouchProductAbstractByAsynchronousAttributeMapOnCreate(
        array $abstractAttrs,
        array $abstractLocalizedAttrs,
        array $concreteAttrs,
        array $concreteLocalizedAttrs
    ) {
        $productAbstractEntity = $this->createProduct($abstractAttrs, $abstractLocalizedAttrs, $concreteAttrs, $concreteLocalizedAttrs);

        $productSearchAttributeMapEntity = $this->createProductSearchAttributeMapEntity('touchProductAbstractByAsynchronousAttributes');
        $this->assertFalse($productSearchAttributeMapEntity->getSynced(), 'Product search attribute map is marked as synced too early!');

        $this->productSearchFacade->touchProductAbstractByAsynchronousAttributeMap();

        $touchCount = SpyTouchQuery::create()
            ->filterByItemId($productAbstractEntity->getIdProductAbstract())
            ->filterByItemType('product_abstract')
            ->count();
        $this->assertEquals(1, $touchCount, 'Failed to touch abstract product!');

        $productSearchAttributeMapEntity->reload();
        $this->assertTrue($productSearchAttributeMapEntity->getSynced(), 'Product search attribute map is not marked as synced!');
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
    public function testTouchProductAbstractByAsynchronousAttributeMapOnUpdate(
        array $abstractAttrs,
        array $abstractLocalizedAttrs,
        array $concreteAttrs,
        array $concreteLocalizedAttrs
    ) {
        $productAbstractEntity = $this->createProduct($abstractAttrs, $abstractLocalizedAttrs, $concreteAttrs, $concreteLocalizedAttrs);

        $productSearchAttributeMapEntity = $this->createProductSearchAttributeMapEntity('touchProductAbstractByAsynchronousAttributes', true);
        $this->assertTrue($productSearchAttributeMapEntity->getSynced(), 'Product search attribute map is not marked as synced!');

        $productSearchAttributeMapEntity
            ->setSynced(false)
            ->save();

        $this->productSearchFacade->touchProductAbstractByAsynchronousAttributeMap();

        $touchCount = SpyTouchQuery::create()
            ->filterByItemId($productAbstractEntity->getIdProductAbstract())
            ->filterByItemType('product_abstract')
            ->count();
        $this->assertEquals(1, $touchCount, 'Failed to touch abstract product!');

        $productSearchAttributeMapEntity->reload();
        $this->assertTrue($productSearchAttributeMapEntity->getSynced(), 'Product search attribute map is not marked as synced!');
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
    public function testTouchProductAbstractByAsynchronousAttributeMapOnDelete(
        array $abstractAttrs,
        array $abstractLocalizedAttrs,
        array $concreteAttrs,
        array $concreteLocalizedAttrs
    ) {
        $productAbstractEntity = $this->createProduct($abstractAttrs, $abstractLocalizedAttrs, $concreteAttrs, $concreteLocalizedAttrs);

        $productSearchAttributeMapEntity = $this->createProductSearchAttributeMapEntity('touchProductAbstractByAsynchronousAttributes');
        $productSearchAttributeMapEntity->delete();

        $this->productSearchFacade->touchProductAbstractByAsynchronousAttributeMap();

        $touchCount = SpyTouchQuery::create()
            ->filterByItemId($productAbstractEntity->getIdProductAbstract())
            ->filterByItemType('product_abstract')
            ->count();
        $this->assertEquals(1, $touchCount, 'Failed to touch abstract product!');
    }

    /**
     * @param string $attributeKey
     * @param bool $synced
     *
     * @return \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMap
     */
    protected function createProductSearchAttributeMapEntity($attributeKey, $synced = false)
    {
        $productAttributeKeyEntity = $this->createProductAttributeKeyEntity($attributeKey);

        $productSearchAttributeMapEntity = new SpyProductSearchAttributeMap();
        $productSearchAttributeMapEntity
            ->setFkProductAttributeKey($productAttributeKeyEntity->getIdProductAttributeKey())
            ->setTargetField(PageIndexMap::FULL_TEXT)
            ->setSynced($synced)
            ->save();

        return $productSearchAttributeMapEntity;
    }
}
