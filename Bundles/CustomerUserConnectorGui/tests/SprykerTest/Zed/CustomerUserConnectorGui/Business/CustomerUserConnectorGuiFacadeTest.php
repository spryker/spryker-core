<?php

namespace SprykerTest\Zed\CustomerUserConnectorGui\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\CustomerUserConnectionUpdateTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerUserConnectorGui
 * @group Business
 * @group Facade
 * @group CustomerUserConnectorGuiFacadeTest
 * Add your own group annotations below this line
 */
class CustomerUserConnectorGuiFacadeTest extends Unit
{

    /**
     * @var \SprykerTest\Zed\CustomerUserConnectorGui\CustomerUserConnectorGuiBusinessTester
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

        $this->assertEquals($expectedAssignedCustomerIds, $actualAssignedCustomerIds);
    }

    /**
     * @return \Spryker\Zed\CustomerUserConnectorGui\Business\CustomerUserConnectorGuiFacadeInterface
     */
    protected function getFacade()
    {
        return $this->tester->getLocator()->customerUserConnectorGui()->facade();
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
                return $customer->getIdCustomer(); },
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
