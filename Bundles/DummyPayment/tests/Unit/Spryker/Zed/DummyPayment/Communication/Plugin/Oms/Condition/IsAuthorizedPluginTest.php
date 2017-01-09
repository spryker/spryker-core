<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Condition;

use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use PHPUnit_Framework_TestCase;
use Spryker\Shared\DummyPayment\DummyPaymentConstants;
use Spryker\Zed\DummyPayment\Communication\Plugin\Oms\Condition\IsAuthorizedPlugin;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group DummyPayment
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group Condition
 * @group IsAuthorizedPluginTest
 */
class IsAuthorizedPluginTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCheckReturnTrueWhenLastNameValid()
    {
        $isAuthorizedPlugin = new IsAuthorizedPlugin();
        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity->setLastName('Valid');
        $salesOrderItemEntity = new SpySalesOrderItem();
        $salesOrderItemEntity->setOrder($salesOrderEntity);

        $this->assertTrue($isAuthorizedPlugin->check($salesOrderItemEntity));
    }

    /**
     * @return void
     */
    public function testCheckReturnFalseWhenLastNameInvalid()
    {
        $isAuthorizedPlugin = new IsAuthorizedPlugin();
        $salesOrderEntity = new SpySalesOrder();
        $salesOrderEntity->setLastName(DummyPaymentConstants::LAST_NAME_FOR_INVALID_TEST);
        $salesOrderItemEntity = new SpySalesOrderItem();
        $salesOrderItemEntity->setOrder($salesOrderEntity);

        $this->assertFalse($isAuthorizedPlugin->check($salesOrderItemEntity));
    }

}
