<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Refund\Persistence;

use Orm\Zed\Refund\Persistence\SpyRefundQuery;
use PHPUnit_Framework_TestCase;
use Spryker\Zed\Refund\Persistence\RefundQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Refund
 * @group Persistence
 * @group RefundQueryContainerTest
 * Add your own group annotations below this line
 */
class RefundQueryContainerTest extends PHPUnit_Framework_TestCase
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
