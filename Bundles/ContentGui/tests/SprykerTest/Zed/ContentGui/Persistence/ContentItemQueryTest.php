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
        $this->tester->createBannerContentItem('br-test1');
        $selectedContentItem = $this->tester->createBannerContentItem('br-test2');
        $this->tester->createBannerContentItem('br-test3');

        // Act
        $contentQuery = SpyContentQuery::create();
        $keyColumn = SpyContentTableMap::COL_KEY;
        $selectedKey = sprintf("(CASE WHEN $keyColumn = '%s' THEN 0 ELSE 1 END)", $selectedContentItem->getKey());

        $result = $contentQuery->filterByContentTypeKey($selectedContentItem->getContentTypeKey())
            ->withColumn($selectedKey, 'selectedKey')
            ->orderBy('selectedKey')
            ->orderBy(SpyContentTableMap::COL_ID_CONTENT)
            ->findOne();

        // Assert
        $this->assertNotNull($result);
        $this->assertEquals($selectedContentItem->getKey(), $result->getKey());
    }
}
