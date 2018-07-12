<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductLabelSearch
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductLabelSearchListenerTest
 * Add your own group annotations below this line
 */
class ProductLabelSearchListenerTest extends Unit
{
    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp()
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
    }

    /**
     * @return void
     */
    public function testQueryProductLabelByProductLabelIds()
    {
        $productLabelSearchQueryContainer = new ProductLabelSearchQueryContainer();
        $result = $productLabelSearchQueryContainer->queryProductLabelByProductLabelIds([1])->count();

        $this->assertSame(48, $result);
    }
}
