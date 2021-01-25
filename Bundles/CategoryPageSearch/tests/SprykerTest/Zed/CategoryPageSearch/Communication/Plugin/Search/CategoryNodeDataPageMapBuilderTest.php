<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\Map\TableMap;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Search\CategoryNodeDataPageMapBuilder;
use Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainer;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilder;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CategoryPageSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CategoryNodeDataPageMapBuilderTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\CategoryPageSearch\CategoryPageSearchCommunicationTester $tester
 */
class CategoryNodeDataPageMapBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildPageMapWillReturnCorrectTransfer(): void
    {
        // Arrange
        $categoryNodeDataPageMapBuilder = new CategoryNodeDataPageMapBuilder();
        $categoryNode = $this->getCategoryNodeTreeByIdCategoryTreeForLocale(1, 46);

        // Act
        $pageMapTransfer = $categoryNodeDataPageMapBuilder->buildPageMap(new PageMapBuilder(), $categoryNode->toArray(TableMap::TYPE_FIELDNAME, true, [], true), (new LocaleTransfer())->setIdLocale(46));

        // Assert
        $this->assertSame(3, count($pageMapTransfer->getFullText()));
        $this->assertSame('Demoshop', $pageMapTransfer->getFullTextBoosted()[0]);
    }

    /**
     * @param int $idCategoryNode
     * @param int $idLocale
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryNode
     */
    protected function getCategoryNodeTreeByIdCategoryTreeForLocale(int $idCategoryNode, int $idLocale): SpyCategoryNode
    {
        return SpyCategoryNodeQuery::create()
            ->filterByIdCategoryNode($idCategoryNode)
            ->joinWithSpyUrl()
            ->joinWithCategory()
            ->joinWith('Category.Attribute')
            ->joinWith('Category.CategoryTemplate')
            ->where(SpyCategoryAttributeTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyUrlTableMap::COL_FK_LOCALE . ' = ?', $idLocale)
            ->where(SpyCategoryTableMap::COL_IS_ACTIVE . ' = ?', true)
            ->where(SpyCategoryTableMap::COL_IS_IN_MENU . ' = ?', true)
            ->orderByIdCategoryNode()
            ->findOne();
    }
}
