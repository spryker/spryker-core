<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Sales;

use Codeception\Actor;
use Generated\Shared\DataBuilder\OrderListRequestBuilder;
use Generated\Shared\Transfer\OrderListRequestTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesBusinessTester extends Actor
{
    use _generated\SalesBusinessTesterActions;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\OrderListRequestTransfer
     */
    public function createOrderListRequestTransfer(array $seed): OrderListRequestTransfer
    {
        return (new OrderListRequestBuilder($seed))
            ->build();
    }
}
