<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomersRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestAddressBuilder;
use Generated\Shared\Transfer\AddressesTransfer;
use Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeBridge;
use Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomersRestApi
 * @group Business
 * @group GetCustomerAddressTest
 * Add your own group annotations below this line
 */
class GetCustomerAddressTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_ADDRESS_UUID_1 = 'FAKE_ADDRESS_UUID_1';

    /**
     * @var string
     */
    protected const FAKE_ADDRESS_UUID_2 = 'FAKE_ADDRESS_UUID_2';

    /**
     * @var string
     */
    protected const FAKE_ADDRESS_UUID_3 = 'FAKE_ADDRESS_UUID_3';

    /**
     * @var string
     */
    protected const FAKE_ADDRESS_UUID_4 = 'FAKE_ADDRESS_UUID_4';

    /**
     * @var \SprykerTest\Zed\CustomersRestApi\CustomersRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCustomerAddressInCaseWhenAddressWasFound(): void
    {
        // Arrange
        $addressesTransfer = $this->getFakeAddresses();
        $quoteTransfer = (new QuoteBuilder())->withCustomer()->build();
        $restAddressTransfer = (new RestAddressBuilder())->build()->setId(static::FAKE_ADDRESS_UUID_2);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory $customersRestApiBusinessFactoryMock */
        $customersRestApiBusinessFactoryMock = $this->getMockBuilder(CustomersRestApiBusinessFactory::class)
            ->onlyMethods(['getCustomerFacade'])
            ->getMock();

        $customersRestApiBusinessFactoryMock
            ->method('getCustomerFacade')
            ->willReturn($this->getCustomerFacadeMock($addressesTransfer));

        // Act
        $addressTransfer = $this->tester->getFacadeMock($customersRestApiBusinessFactoryMock)
            ->getCustomerAddress($restAddressTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($addressesTransfer->getAddresses()->offsetGet(1), $addressTransfer);
    }

    /**
     * @return void
     */
    public function testGetCustomerAddressInCaseWhenAddressWasNotCompared(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->withCustomer()->build();
        $restAddressTransfer = (new RestAddressBuilder())->build()->setId(static::FAKE_ADDRESS_UUID_4);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory $customersRestApiBusinessFactoryMock */
        $customersRestApiBusinessFactoryMock = $this->getMockBuilder(CustomersRestApiBusinessFactory::class)
            ->onlyMethods(['getCustomerFacade'])
            ->getMock();

        $customersRestApiBusinessFactoryMock
            ->method('getCustomerFacade')
            ->willReturn($this->getCustomerFacadeMock($this->getFakeAddresses()));

        // Act
        $addressTransfer = $this->tester->getFacadeMock($customersRestApiBusinessFactoryMock)
            ->getCustomerAddress($restAddressTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($restAddressTransfer->getAddress1(), $addressTransfer->getAddress1());
    }

    /**
     * @return void
     */
    public function testGetCustomerAddressInCaseWhenCustomerAddressesWasNotFound(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteBuilder())->withCustomer()->build();
        $restAddressTransfer = (new RestAddressBuilder())->build()->setId(static::FAKE_ADDRESS_UUID_1);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomersRestApi\Business\CustomersRestApiBusinessFactory $customersRestApiBusinessFactoryMock */
        $customersRestApiBusinessFactoryMock = $this->getMockBuilder(CustomersRestApiBusinessFactory::class)
            ->onlyMethods(['getCustomerFacade'])
            ->getMock();

        $customersRestApiBusinessFactoryMock
            ->method('getCustomerFacade')
            ->willReturn($this->getCustomerFacadeMock());

        // Act
        $addressTransfer = $this->tester->getFacadeMock($customersRestApiBusinessFactoryMock)
            ->getCustomerAddress($restAddressTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($restAddressTransfer->getAddress1(), $addressTransfer->getAddress1());
    }

    /**
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function getFakeAddresses(): AddressesTransfer
    {
        return (new AddressesTransfer())
            ->addAddress((new AddressBuilder())->build()->setUuid(static::FAKE_ADDRESS_UUID_1))
            ->addAddress((new AddressBuilder())->build()->setUuid(static::FAKE_ADDRESS_UUID_2))
            ->addAddress((new AddressBuilder())->build()->setUuid(static::FAKE_ADDRESS_UUID_3));
    }

    /**
     * @param \Generated\Shared\Transfer\AddressesTransfer|null $addressesTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CustomersRestApi\Dependency\Facade\CustomersRestApiToCustomerFacadeInterface
     */
    protected function getCustomerFacadeMock(?AddressesTransfer $addressesTransfer = null): CustomersRestApiToCustomerFacadeInterface
    {
        $customerFacadeMock = $this->getMockBuilder(CustomersRestApiToCustomerFacadeBridge::class)
            ->onlyMethods(['getAddresses'])
            ->disableOriginalConstructor()
            ->getMock();

        $customerFacadeMock
            ->method('getAddresses')
            ->willReturn($addressesTransfer ?? new AddressesTransfer());

        return $customerFacadeMock;
    }
}
