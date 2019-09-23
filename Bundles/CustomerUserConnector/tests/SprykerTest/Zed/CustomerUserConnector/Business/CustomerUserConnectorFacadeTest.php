<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerUserConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerUserConnector
 * @group Business
 * @group Facade
 * @group CustomerUserConnectorFacadeTest
 * Add your own group annotations below this line
 */
class CustomerUserConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomerUserConnector\CustomerUserConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateCustomerUserConnectionUpdatesCustomers()
    {
        // Assign
        $userTransfer = $this->tester->haveUser();
        $assignedCustomerIds = $this->assignCustomers($userTransfer->getIdUser(), 6);
        $availableCustomerIds = $this->createCustomers(6);

        $idsCustomerToAssign = array_slice($availableCustomerIds, 0, 3);
        $idsCustomerToDeAssign = array_slice($assignedCustomerIds, 0, 3);

        $customerUserConnectionTransfer = (new CustomerUserConnectionUpdateTransfer())
            ->setIdUser($userTransfer->getIdUser())
            ->setIdCustomersToAssign($idsCustomerToAssign)
            ->setIdCustomersToDeAssign($idsCustomerToDeAssign);
        $expectedAssignedCustomerIds = array_merge(array_diff($assignedCustomerIds, $idsCustomerToDeAssign), $idsCustomerToAssign);

        // Act
        $this->getFacade()->updateCustomerUserConnection($customerUserConnectionTransfer);

        // Assert
        $actualAssignedCustomerIds = $this->getAssignedCustomerIds($userTransfer->getIdUser());

        sort($expectedAssignedCustomerIds);
        sort($actualAssignedCustomerIds);
        $this->assertEquals($expectedAssignedCustomerIds, $actualAssignedCustomerIds);
    }

    /**
     * @return \Spryker\Zed\CustomerUserConnector\Business\CustomerUserConnectorFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getLocator()->customerUserConnector()->facade();
    }

    /**
     * @param int $idUser
     * @param int $numberOfCustomers
     *
     * @return int[]
     */
    protected function assignCustomers($idUser, $numberOfCustomers)
    {
        $idCustomersToAssign = $this->createCustomers($numberOfCustomers);

        $customerUserConnectionTransfer = (new CustomerUserConnectionUpdateTransfer())
            ->setIdUser($idUser)
            ->setIdCustomersToAssign($idCustomersToAssign)
            ->setIdCustomersToDeAssign([]);

        $this->getFacade()->updateCustomerUserConnection($customerUserConnectionTransfer);

        return $idCustomersToAssign;
    }

    /**
     * @param int $numberOfCustomers
     *
     * @return int[]
     */
    protected function createCustomers($numberOfCustomers)
    {
        $customers = [];
        for ($i = 0; $i < $numberOfCustomers; $i++) {
            $customers[] = $this->tester->haveCustomer();
        }

        $idCustomers = array_map(
            function (CustomerTransfer $customer) {
                return $customer->getIdCustomer();
            },
            $customers
        );

        return $idCustomers;
    }

    /**
     * @param int $idUser
     *
     * @return int[]
     */
    protected function getAssignedCustomerIds($idUser)
    {
        $customerEntities = (new SpyCustomerQuery())->findByFkUser($idUser);

        $idsCustomer = [];
        foreach ($customerEntities as $customerEntity) {
            $idsCustomer[] = $customerEntity->getIdCustomer();
        }

        return $idsCustomer;
    }
}
