<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerGroup\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Generated\Shared\Transfer\CustomerGroupToCustomerTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomerQuery;
use Spryker\Zed\CustomerGroup\Business\CustomerGroupFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerGroup
 * @group Business
 * @group Facade
 * @group CustomerGroupFacadeTest
 * Add your own group annotations below this line
 */
class CustomerGroupFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testGetValid()
    {
        $customerGroupEntity = $this->creteCustomerGroup();

        $customerEntity = $this->createCustomer();

        $customerGroupToCustomerEntity = new SpyCustomerGroupToCustomer();
        $customerGroupToCustomerEntity->setFkCustomerGroup($customerGroupEntity->getIdCustomerGroup());
        $customerGroupToCustomerEntity->setFkCustomer($customerEntity->getIdCustomer());
        $customerGroupToCustomerEntity->save();

        $customerGroupFacade = $this->createCustomerGroupFacade();

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($customerGroupEntity->getIdCustomerGroup());

        $resultTransfer = $customerGroupFacade->get($customerGroupTransfer);
        $this->assertSame($customerGroupEntity->getName(), $resultTransfer->getName());

        $customers = $resultTransfer->getCustomers();
        foreach ($customers as $customer) {
            $this->assertSame($customerEntity->getIdCustomer(), $customer->getFkCustomer());
        }
    }

    /**
     * @return void
     */
    public function testFindCustomerGroupByIdCustomerShouldReturnGroupTransferWhenValidIdGiven()
    {
        $customerGroupEntity = $this->creteCustomerGroup();

        $customerEntity = $this->createCustomer();

        $customerGroupToCustomerEntity = new SpyCustomerGroupToCustomer();
        $customerGroupToCustomerEntity->setFkCustomerGroup($customerGroupEntity->getIdCustomerGroup());
        $customerGroupToCustomerEntity->setFkCustomer($customerEntity->getIdCustomer());
        $customerGroupToCustomerEntity->save();

        $customerGroupFacade = $this->createCustomerGroupFacade();
        $customerGroupTransfer = $customerGroupFacade->findCustomerGroupByIdCustomer($customerEntity->getIdCustomer());

        $this->assertNotEmpty($customerGroupTransfer);
        $this->assertEquals($customerGroupEntity->getName(), $customerGroupTransfer->getName());
    }

    /**
     * @return void
     */
    public function testAddValid()
    {
        $customerGroupFacade = $this->createCustomerGroupFacade();

        $customerEntityOne = $this->createCustomer();
        $customerEntityTwo = $this->createCustomer('two@second.de', 'Second', 'Two', 'two');

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->setName('Foo');
        $customerGroupTransfer->setDescription('Descr');

        $customerGroupToCustomerTransfer = new CustomerGroupToCustomerTransfer();
        $customerGroupToCustomerTransfer->setFkCustomer($customerEntityOne->getIdCustomer());
        $customerGroupTransfer->addCustomer($customerGroupToCustomerTransfer);

        $customerGroupToCustomerTransfer = new CustomerGroupToCustomerTransfer();
        $customerGroupToCustomerTransfer->setFkCustomer($customerEntityTwo->getIdCustomer());
        $customerGroupTransfer->addCustomer($customerGroupToCustomerTransfer);

        $resultTransfer = $customerGroupFacade->add($customerGroupTransfer);
        $this->assertNotEmpty($resultTransfer->getIdCustomerGroup());
    }

    /**
     * @return void
     */
    public function testUpdateValid()
    {
        $customerGroupFacade = $this->createCustomerGroupFacade();

        $customerGroup = [
            'name' => 'Test' . time(),
            'description' => 'Test' . time(),
        ];

        $customerGroupEntity = new SpyCustomerGroup();
        $customerGroupEntity->fromArray($customerGroup);
        $customerGroupEntity->save();

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->fromArray($customerGroupEntity->toArray(), true);

        $customerGroupTransfer->setName('Foo');
        $customerGroupTransfer->setDescription('Descr');

        $customerGroupFacade->update($customerGroupTransfer);

        $customerGroupQuery = SpyCustomerGroupQuery::create();
        $updatedCustomerGroupEntity = $customerGroupQuery->filterByIdCustomerGroup($customerGroupEntity->getIdCustomerGroup())->findOne();

        $this->assertSame('Foo', $updatedCustomerGroupEntity->getName());
        $this->assertSame('Descr', $updatedCustomerGroupEntity->getDescription());
    }

    /**
     * We remove one customer and add another one.
     *
     * @return void
     */
    public function testUpdateCustomersValid()
    {
        $customerGroupFacade = $this->createCustomerGroupFacade();

        $customerGroup = [
            'name' => 'Test' . time(),
        ];

        $customerGroupEntity = new SpyCustomerGroup();
        $customerGroupEntity->fromArray($customerGroup);
        $customerGroupEntity->save();

        $customerEntityOne = $this->createCustomer();

        $customerGroupToCustomerEntity = new SpyCustomerGroupToCustomer();
        $customerGroupToCustomerEntity->setFkCustomerGroup($customerGroupEntity->getIdCustomerGroup());
        $customerGroupToCustomerEntity->setFkCustomer($customerEntityOne->getIdCustomer());
        $customerGroupToCustomerEntity->save();

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->fromArray($customerGroupEntity->toArray(), true);

        $customerEntityTwo = $this->createCustomer('two@second.de', 'Second', 'Two', 'two');
        $customerGroupTransfer->setCustomerAssignment(
            (new CustomerGroupToCustomerAssignmentTransfer())
                ->addIdCustomerToAssign($customerEntityTwo->getIdCustomer())
                ->addIdCustomerToDeAssign($customerEntityOne->getIdCustomer())
        );

        $customerGroupTransfer->setName('Foo');
        $customerGroupTransfer->setDescription('Descr');

        $customerGroupFacade->update($customerGroupTransfer);

        $customerGroupToCustomerQuery = SpyCustomerGroupToCustomerQuery::create();
        $customerGroupToCustomerArray = $customerGroupToCustomerQuery->filterByFkCustomerGroup($customerGroupEntity->getIdCustomerGroup())->find()->toArray();

        $this->assertCount(1, $customerGroupToCustomerArray);
        $this->assertSame($customerEntityTwo->getIdCustomer(), $customerGroupToCustomerArray[0]['FkCustomer']);
    }

    /**
     * @return void
     */
    public function testDeleteValid()
    {
        $customerGroupFacade = $this->createCustomerGroupFacade();

        $customerGroupEntity = $this->creteCustomerGroup();

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($customerGroupEntity->getIdCustomerGroup());

        $customerGroupFacade->delete($customerGroupTransfer);

        $customerGroupQuery = SpyCustomerGroupQuery::create();
        $customerGroupEntity = $customerGroupQuery->filterByIdCustomerGroup($customerGroupEntity->getIdCustomerGroup())->findOne();

        $this->assertNull($customerGroupEntity);
    }

    /**
     * @return void
     */
    public function testRemoveCustomersFromGroupValid()
    {
        $customerGroupFacade = $this->createCustomerGroupFacade();

        $customerGroupEntity = $this->creteCustomerGroup();

        $customerEntity = $this->createCustomer();

        $customerGroupToCustomerEntity = new SpyCustomerGroupToCustomer();
        $customerGroupToCustomerEntity->setFkCustomerGroup($customerGroupEntity->getIdCustomerGroup());
        $customerGroupToCustomerEntity->setFkCustomer($customerEntity->getIdCustomer());
        $customerGroupToCustomerEntity->save();

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($customerGroupEntity->getIdCustomerGroup());
        $customerGroupTransfer->setCustomerAssignment(
            (new CustomerGroupToCustomerAssignmentTransfer())
                ->addIdCustomerToDeAssign($customerEntity->getIdCustomer())
        );

        $customerGroupFacade->removeCustomersFromGroup($customerGroupTransfer);

        $customerGroupToCustomerQuery = SpyCustomerGroupToCustomerQuery::create();
        $customerEntity = $customerGroupToCustomerQuery
            ->filterByFkCustomerGroup($customerGroupEntity->getIdCustomerGroup())
            ->filterByFkCustomer($customerEntity->getIdCustomer())
            ->findOne();

        $this->assertNull($customerEntity);
    }

    /**
     * @return void
     */
    public function testRemoveCustomerFromAllGroups()
    {
        $customerGroupFacade = $this->createCustomerGroupFacade();

        $customerGroupEntity1 = $this->creteCustomerGroup();
        $customerGroupEntity2 = $this->creteCustomerGroup();

        $customerEntity = $this->createCustomer();

        $this->createCustomerToGroup($customerEntity->getIdCustomer(), $customerGroupEntity1->getIdCustomerGroup());
        $this->createCustomerToGroup($customerEntity->getIdCustomer(), $customerGroupEntity2->getIdCustomerGroup());

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setIdCustomer($customerEntity->getIdCustomer());

        $customerGroupFacade->removeCustomerFromAllGroups($customerTransfer);

        $customerGroupTransfer = $customerGroupFacade->findCustomerGroupByIdCustomer($customerEntity->getIdCustomer());

        $this->assertNull($customerGroupTransfer);
    }

    /**
     * @param string $email
     * @param string $lastName
     * @param string $firstName
     * @param string $reference
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function createCustomer($email = 'one@first.de', $lastName = 'First', $firstName = 'One', $reference = 'one')
    {
        $customerEntity = new SpyCustomer();
        $customerEntity->setFirstName($firstName);
        $customerEntity->setFirstName($lastName);
        $customerEntity->setCustomerReference($reference);
        $customerEntity->setEmail($email);

        $customerEntity->save();

        return $customerEntity;
    }

    /**
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup
     */
    protected function creteCustomerGroup()
    {
        $customerGroupEntity = (new SpyCustomerGroup())
            ->setName('Test' . uniqid(true));

        $customerGroupEntity->save();

        return $customerGroupEntity;
    }

    /**
     * @return \Spryker\Zed\CustomerGroup\Business\CustomerGroupFacade
     */
    protected function createCustomerGroupFacade()
    {
        return new CustomerGroupFacade();
    }

    /**
     * @param int $idCustomer
     * @param int $idCustomerGroup
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer
     */
    protected function createCustomerToGroup($idCustomer, $idCustomerGroup)
    {
        $customerGroupToCustomerEntity = (new SpyCustomerGroupToCustomer())
            ->setFkCustomerGroup($idCustomerGroup)
            ->setFkCustomer($idCustomer);

        $customerGroupToCustomerEntity->save();

        return $customerGroupToCustomerEntity;
    }
}
