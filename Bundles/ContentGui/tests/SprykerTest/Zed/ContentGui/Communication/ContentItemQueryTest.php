<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui\Communication;

use Codeception\Test\Unit;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\ContentGui\Communication\Table\ContentTableConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ContentGui
 * @group Communication
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
        $tableMock = new ContentByTypeTableMock('Banner', $contentQuery, $selectedContentItem->getKey());
        $result = $tableMock->fetchData();

        // Assert
        $this->assertNotEmpty($result);
        $this->assertEquals($selectedContentItem->getKey(), $result[0][ContentTableConstants::COL_KEY]);
    }
}
