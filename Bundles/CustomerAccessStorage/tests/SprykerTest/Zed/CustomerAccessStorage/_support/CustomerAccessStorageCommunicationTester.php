<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\CustomerAccessStorage;

use Codeception\Actor;
use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class CustomerAccessStorageCommunicationTester extends Actor
{
    use _generated\CustomerAccessStorageCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureUnauthenticatedCustomerAccessTableIsEmpty(): void
    {
        $this->getUnauthenticatedCustomerAccessQuery()->deleteAll();
    }

    /**
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery
     */
    protected function getUnauthenticatedCustomerAccessQuery(): SpyUnauthenticatedCustomerAccessQuery
    {
        return SpyUnauthenticatedCustomerAccessQuery::create();
    }
}
