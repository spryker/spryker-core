<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
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
 */
class CategoryNodeDataPageMapBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildPageMapWillReturnCorrectTransfer()
    {
        $query = new CategoryPageSearchQueryContainer();
        $categoryNodeDataPageMapBuilder = new CategoryNodeDataPageMapBuilder();
        $categoryNode = $query->queryCategoryNodeTree([1], 46)->orderByIdCategoryNode()->find()->getFirst();
        $pageMapTransfer = $categoryNodeDataPageMapBuilder->buildPageMap(new PageMapBuilder(), $categoryNode->toArray(TableMap::TYPE_FIELDNAME, true, [], true), (new LocaleTransfer())->setIdLocale(46));

        $this->assertSame(3, count($pageMapTransfer->getFullText()));
        $this->assertSame('Demoshop', $pageMapTransfer->getFullTextBoosted()[0]);
    }
}
