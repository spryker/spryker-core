<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group CustomerFacadeSubTest
 * Add your own group annotations below this line
 */
class CustomerFacadeSubTest extends Unit
{
    public const TESTER_FK_COUNTRY_GERMANY = '60';

    /**
     * @var \SprykerTest\Zed\Customer\CustomerBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $businessLayerDependencies;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->customerFacade = new CustomerFacade();
        $this->customerFacade->setFactory($this->getBusinessFactory());
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getBusinessFactory()
    {
        $customerBusinessFactory = new CustomerBusinessFactory();
        $customerBusinessFactory->setContainer($this->getContainer());

        return $customerBusinessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $dependencyProvider = new CustomerDependencyProvider();
        $this->businessLayerDependencies = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($this->businessLayerDependencies);

        return $this->businessLayerDependencies;
    }

    /**
     * @return void
     */
    public function testAnonymizeCustomer()
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();

        // Act
        $this->customerFacade->anonymizeCustomer($customerTransfer);

        // Assert
        $this->expectException(CustomerNotFoundException::class);
        $this->customerFacade->getCustomer($customerTransfer);
    }

    /**
     * @return void
     */
    public function testFindCustomerByReference()
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();

        // Act
        $customerResponseTransfer = $this->customerFacade->findCustomerByReference($customerTransfer->getCustomerReference());

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertEquals($customerTransfer->getCustomerReference(), $customerResponseTransfer->getCustomerTransfer()->getCustomerReference());
    }

    /**
     * @return void
     */
    public function testGetAddressesHasCountry()
    {
        // Assign
        $customerTransfer = $this->tester->haveCustomer();
        $this->customerFacade->createAddressAndUpdateCustomerDefaultAddresses($customerTransfer->getShippingAddress()[0]);

        // Act
        $addressesTransfer = $this->customerFacade->getAddresses($customerTransfer);
        $addressTransfer = $addressesTransfer->getAddresses()[0];

        // Assert
        $this->assertEquals(self::TESTER_FK_COUNTRY_GERMANY, $addressTransfer->getCountry()->getIdCountry());
    }
}