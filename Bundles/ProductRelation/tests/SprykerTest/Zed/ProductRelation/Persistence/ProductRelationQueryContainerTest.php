<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductRelation\Persistence;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationPersistenceFactory;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ProductRelation
 * @group Persistence
 * @group ProductRelationQueryContainerTest
 * Add your own group annotations below this line
 */
class ProductRelationQueryContainerTest extends Unit
{
    protected const LOCALE_NAME = 'xxx';

    /**
     * @var \SprykerTest\Zed\ProductRelation\ProductRelationPersistenceTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->productRelationQueryContainer = new ProductRelationQueryContainer();
        $this->productRelationQueryContainer->setFactory(new ProductRelationPersistenceFactory());
    }

    /**
     * @return void
     */
    public function testQueryProductRelationsReturnCorrectQuery(): void
    {
        $productRelationQueryContainer = new ProductRelationQueryContainer();
        $productRelationQueryContainer->setFactory(new ProductRelationPersistenceFactory());
        $query = $productRelationQueryContainer->queryAllProductRelations();

        $this->assertInstanceOf(SpyProductRelationQuery::class, $query);
    }

    /**
     * @return void
     */
    public function testQueryProductRelationsWithProductCountReturnsCorrectData(): void
    {
        // Arrange
        $productRelationTypeTransfer = $this->tester->haveProductRelationType();

        $localeTransfer = $this->tester->haveLocale([LocaleTransfer::LOCALE_NAME => static::LOCALE_NAME]);
        $localizedAttributes = (new LocalizedAttributesBuilder([
            LocalizedAttributesTransfer::LOCALE => $localeTransfer,
        ]))->build()->toArray();
        $productAbstractTransfer1 = $this->tester->haveProductAbstract();
        $productAbstractTransfer2 = $this->tester->haveProductAbstract();
        $relatedProductAbstractTransfer1 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::LOCALIZED_ATTRIBUTES => [$localizedAttributes],
        ]);
        $relatedProductAbstractTransfer2 = $this->tester->haveProductAbstract([
            ProductAbstractTransfer::LOCALIZED_ATTRIBUTES => [$localizedAttributes],
        ]);

        $productRelationTransfer11 = $this->tester->haveProductRelation(
            $productAbstractTransfer1->getSku(),
            $relatedProductAbstractTransfer1->getIdProductAbstract(),
            'test-relation-11',
            $productRelationTypeTransfer->getKey()
        );
        $productRelationTransfer12 = $this->tester->haveProductRelation(
            $productAbstractTransfer1->getSku(),
            $relatedProductAbstractTransfer2->getIdProductAbstract(),
            'test-relation-12',
            $productRelationTypeTransfer->getKey()
        );
        $productRelationTransfer21 = $this->tester->haveProductRelation(
            $productAbstractTransfer2->getSku(),
            $relatedProductAbstractTransfer2->getIdProductAbstract(),
            'test-relation-21',
            $productRelationTypeTransfer->getKey()
        );

        // Act
        $result = $this->productRelationQueryContainer
            ->queryProductRelationsWithProductCount($localeTransfer->getIdLocale())
            ->find()
            ->toArray();

        // Assert
        $this->assertCount(3, $result);
        $resultProductRelationIds = array_map(
            'intval',
            array_column($result, SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION)
        );
        $this->assertContains($productRelationTransfer11->getIdProductRelation(), $resultProductRelationIds);
        $this->assertContains($productRelationTransfer12->getIdProductRelation(), $resultProductRelationIds);
        $this->assertContains($productRelationTransfer21->getIdProductRelation(), $resultProductRelationIds);
    }
}
