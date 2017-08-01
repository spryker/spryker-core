<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DummyPayment\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Condition\IsPayedPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group Condition
 * @group IsPayedPluginTest
 * Add your own group annotations below this line
 */
class IsPayedPluginTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCheckReturnAlwaysTrue()
    {
        $isPayedPlugin = new IsPayedPlugin();
        $salesOrderItemEntity = new SpySalesOrderItem();

        $this->assertTrue($isPayedPlugin->check($salesOrderItemEntity));
    }

}
