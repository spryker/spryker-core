<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Agent;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\Agent\Business\AgentFacadeInterface getFacade() : \Spryker\Zed\Kernel\Business\AbstractFacade
 *
 * @SuppressWarnings(PHPMD)
 */
class AgentBusinessTester extends Actor
{
    use _generated\AgentBusinessTesterActions;

    public const CUSTOMER_FIRST_NAME = 'customerFirstName';

    /**
     * @param int $count
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer[]
     */
    public function createCustomers(int $count = 10): array
    {
        $customerTransfers = [];

        while ($count) {
            $customerTransfers[] = $this->haveCustomer([CustomerTransfer::FIRST_NAME => static::CUSTOMER_FIRST_NAME]);
            $count--;
        }

        return $customerTransfers;
    }
}
