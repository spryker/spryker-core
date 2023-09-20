<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Kernel\Container;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group Facade
 * @group AbstractCustomerAddressFacadeTest
 * Add your own group annotations below this line
 */
abstract class AbstractCustomerAddressFacadeTest extends AbstractCustomerFacadeTest
{
    /**
     * @var string
     */
    protected const TESTER_EMAIL = 'tester@spryker.com';

    /**
     * @var string
     */
    protected const TESTER_PASSWORD = 'testpassworD1$';

    /**
     * @var string
     */
    protected const TESTER_NAME = 'Tester';

    /**
     * @var string
     */
    protected const TESTER_CITY = 'Testcity';

    /**
     * @var string
     */
    protected const TESTER_ADDRESS1 = 'Testerstreet 23';

    /**
     * @var string
     */
    protected const TESTER_ZIP_CODE = '42';

    /**
     * @var int
     */
    protected const TESTER_FK_COUNTRY_GERMANY = 60;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected CustomerFacadeInterface $customerFacade;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected Container $businessLayerDependencies;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->customerFacade = new CustomerFacade();
        $this->customerFacade->setFactory($this->getBusinessFactory());
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerWithAddress(): CustomerTransfer
    {
        $customerTransfer = $this->tester->createTestCustomer();
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setEmail($customerTransfer->getEmail());
        $addressTransfer->setFirstName(static::TESTER_NAME);
        $addressTransfer->setLastName(static::TESTER_NAME);
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);
        $this->assertNotNull($addressTransfer);

        return $this->tester->getFacade()->getCustomer($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createTestAddress(CustomerTransfer $customerTransfer): AddressTransfer
    {
        $addressTransfer = $this->createTestAddressTransfer($customerTransfer);
        $addressTransfer = $this->customerFacade->createAddress($addressTransfer);

        return $addressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function createTestAddressTransfer(CustomerTransfer $customerTransfer): AddressTransfer
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->setFkCustomer($customerTransfer->getIdCustomer());
        $addressTransfer->setEmail(static::TESTER_EMAIL);
        $addressTransfer->setFirstName(static::TESTER_NAME);
        $addressTransfer->setLastName(static::TESTER_NAME);
        $addressTransfer->setAddress1(static::TESTER_ADDRESS1);
        $addressTransfer->setCity(static::TESTER_CITY);
        $addressTransfer->setZipCode(static::TESTER_ZIP_CODE);

        return $addressTransfer;
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getBusinessFactory(): CustomerBusinessFactory
    {
        $customerBusinessFactory = new CustomerBusinessFactory();
        $customerBusinessFactory->setContainer($this->getContainer());

        return $customerBusinessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer(): Container
    {
        $dependencyProvider = new CustomerDependencyProvider();
        $this->businessLayerDependencies = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($this->businessLayerDependencies);

        $this->businessLayerDependencies[CustomerDependencyProvider::FACADE_MAIL] = $this->getMockBuilder(CustomerToMailInterface::class)->getMock();

        return $this->businessLayerDependencies;
    }
}
