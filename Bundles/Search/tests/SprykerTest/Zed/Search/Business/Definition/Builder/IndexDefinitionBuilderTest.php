<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Search\Business\Definition\Builder;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Search
 * @group Business
 * @group Definition
 * @group Builder
 * @group IndexDefinitionBuilderTest
 * Add your own group annotations below this line
 */
class IndexDefinitionBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Search\SearchBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildReturnsAnArrayOfIndexDefinitionTransfers(): void
    {
        $this->tester->mockConfigMethod('getIndexNameMap', ['page' => 'page', 'customer-page' => 'customer-page', 'product-review' => 'product-review']);
        $indexDefinitionBuilder = $this->tester->getFactory()->createIndexDefinitionBuilder();
        $indexDefinitionTransferCollection = $indexDefinitionBuilder->build();

        $this->assertIsArray($indexDefinitionTransferCollection);
    }
}
