<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui\Persistence;

use Codeception\Test\Unit;
use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;
use Orm\Zed\Content\Persistence\SpyContentQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentGui
 * @group Persistence
 * @group ContentItemQueryTest
 * Add your own group annotations below this line
 */
class ContentItemQueryTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ContentGui\ContentGuiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testOrderContentItemsBySelectedItem(): void
    {
        // Arrange
        $this->tester->createBannerContentItem();
        $selectedContentItem = $this->tester->createBannerContentItem();
        $this->tester->createBannerContentItem();

        // Act
        $contentQuery = SpyContentQuery::create();
        $selectedId = sprintf('(CASE WHEN %s = %d THEN 1 END)', SpyContentTableMap::COL_ID_CONTENT, $selectedContentItem->getIdContent());

        $result = $contentQuery->filterByContentTypeKey($selectedContentItem->getContentTypeKey())
            ->withColumn($selectedId, 'selectedId')
            ->orderBy('selectedId')
            ->orderBy(SpyContentTableMap::COL_ID_CONTENT)
            ->find();

        // Assert
        $this->assertEquals($selectedContentItem->getIdContent(), $result->offsetGet(0)->getIdContent());
    }
}
