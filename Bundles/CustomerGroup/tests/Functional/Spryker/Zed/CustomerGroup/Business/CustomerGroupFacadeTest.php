<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\CustomerGroup\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup;
use Spryker\Zed\CustomerGroup\Business\CustomerGroupFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group CustomerGroup
 * @group Business
 * @group CustomerGroupFacadeTest
 */
class CustomerGroupFacadeTest extends Test
{

    /**
     * @return void
     */
    public function testGetValid()
    {
        $customerGroupEntity = new SpyCustomerGroup();
        $customerGroupEntity->setName('Test' . time());
        $customerGroupEntity->save();

        $customerGroupFacade = new CustomerGroupFacade();

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($customerGroupEntity->getIdCustomerGroup());

        $resultTransfer = $customerGroupFacade->get($customerGroupTransfer);
        $this->assertSame($customerGroupEntity->getName(), $resultTransfer->getName());
    }

    /**
     * @return void
     */
    public function testAddValid()
    {
        $customerGroupFacade = new CustomerGroupFacade();

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->setName('Foo');
        $customerGroupTransfer->setDescription('Descr');

        $resultTransfer = $customerGroupFacade->add($customerGroupTransfer);
        $this->assertNotEmpty($resultTransfer->getIdCustomerGroup());
    }

}
