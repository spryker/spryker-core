<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelSearch\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use PHPUnit\Framework\SkippedTestError;
use Propel\Runtime\Propel;
use Silex\Application;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainer;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

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
        $dbType = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbType !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }

        Propel::disableInstancePooling();
        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @return void
     */
    public function testQueryProductLabelByProductLabelIds()
    {
        $productLabelSearchQueryContainer = new ProductLabelSearchQueryContainer();
        $result = $productLabelSearchQueryContainer->queryProductLabelByProductLabelIds([1])->count();

        $this->assertEquals(48, $result);
    }
}
