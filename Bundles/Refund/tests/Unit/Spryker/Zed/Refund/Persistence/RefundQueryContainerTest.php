<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Zed\Refund\Persistence;

use Orm\Zed\Refund\Persistence\SpyRefundQuery;
use Spryker\Zed\Refund\Persistence\RefundQueryContainer;

/**
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Persistence
 * @group RefundQueryContainer
 */
class RefundQueryContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateRefundQueryShouldReturnRefundQuery()
    {
        $refundQueryContainer = new RefundQueryContainer();

        $this->assertInstanceOf(SpyRefundQuery::class, $refundQueryContainer->queryRefunds());
    }

}
