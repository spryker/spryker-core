<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentGui\Communication\Table;

use Codeception\Test\Unit;
use Orm\Zed\Content\Persistence\SpyContentQuery;
use Spryker\Zed\ContentGui\Communication\Table\ContentByTypeTable;

class ContentByTypeTableTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ContentGui\ContentGuiBusinessTester
     */
    protected $tester;

    public function testOrderContentItems(): void
    {
        $this->tester->createBannerContentItem();
        $selectedContentItem = $this->tester->createBannerContentItem();
        $this->tester->createBannerContentItem();

        $contentByTypeTable = $this->createContentByTypeTable(
            $selectedContentItem->getContentTypeKey(),
            SpyContentQuery::create(),
            $selectedContentItem->getIdContent()
        );

        $tableData = $contentByTypeTable->fetchData();
    }

    public function createContentByTypeTable(string $contentType, SpyContentQuery $contentQuery, int $idContent): ContentByTypeTable
    {
        return new ContentByTypeTable($contentType, $contentQuery, $idContent);
    }
}
