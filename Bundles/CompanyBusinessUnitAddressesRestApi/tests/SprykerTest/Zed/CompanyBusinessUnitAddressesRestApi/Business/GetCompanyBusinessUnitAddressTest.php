<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\RestAddressBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeBridge;
use Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnitAddressesRestApi
 * @group Business
 * @group GetCompanyBusinessUnitAddressTest
 * Add your own group annotations below this line
 */
class GetCompanyBusinessUnitAddressTest extends Unit
{
    /**
     * @var string
     */
    protected const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID = 'FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID';

    /**
     * @var int
     */
    protected const ID_COMPANY = 5555;

    /**
     * @var int
     */
    protected const FAKE_ID_COMPANY = 6666;

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitAddressInCaseAddressWasFound(): void
    {
        // Arrange
        $companyUnitAddressResponseTransfer = $this->getFakeCompanyUnitAddressResponseTransfer();
        $quoteTransfer = (new QuoteBuilder())->withCustomer()->build();

        $quoteTransfer->getCustomer()->setCompanyUserTransfer(
            (new CompanyUserTransfer())->setCompany($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getCompany())
        );

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory $companyBusinessUnitAddressesRestApiBusinessFactoryMock */
        $companyBusinessUnitAddressesRestApiBusinessFactoryMock = $this->getMockBuilder(CompanyBusinessUnitAddressesRestApiBusinessFactory::class)
            ->onlyMethods(['getCompanyUnitAddressFacade'])
            ->getMock();

        $companyBusinessUnitAddressesRestApiBusinessFactoryMock
            ->method('getCompanyUnitAddressFacade')
            ->willReturn($this->getCompanyUnitAddressFacadeMock($companyUnitAddressResponseTransfer));

        // Act
        $addressTransfer = $this->tester->getFacadeMock($companyBusinessUnitAddressesRestApiBusinessFactoryMock)
            ->getCompanyBusinessUnitAddress(
                (new RestAddressBuilder())->build(),
                $quoteTransfer
            );

        // Assert
        $this->assertCompanyBusinessUnitAddress($companyUnitAddressResponseTransfer, $quoteTransfer, $addressTransfer);
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitAddressInCaseCompanyBusinessUnitAddressFromAnotherCompany(): void
    {
        // Arrange
        $restAddressTransfer = (new RestAddressBuilder())->build();
        $quoteTransfer = (new QuoteBuilder())->withCustomer()->build();

        $quoteTransfer->getCustomer()->setCompanyUserTransfer(
            (new CompanyUserTransfer())->setCompany((new CompanyTransfer())->setIdCompany(static::FAKE_ID_COMPANY))
        );

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory $companyBusinessUnitAddressesRestApiBusinessFactoryMock */
        $companyBusinessUnitAddressesRestApiBusinessFactoryMock = $this->getMockBuilder(CompanyBusinessUnitAddressesRestApiBusinessFactory::class)
            ->onlyMethods(['getCompanyUnitAddressFacade'])
            ->getMock();

        $companyBusinessUnitAddressesRestApiBusinessFactoryMock
            ->method('getCompanyUnitAddressFacade')
            ->willReturn($this->getCompanyUnitAddressFacadeMock($this->getFakeCompanyUnitAddressResponseTransfer()));

        // Act
        $addressTransfer = $this->tester->getFacadeMock($companyBusinessUnitAddressesRestApiBusinessFactoryMock)
            ->getCompanyBusinessUnitAddress($restAddressTransfer, $quoteTransfer);

        // Assert
        $this->assertSame($restAddressTransfer->getAddress1(), $addressTransfer->getAddress1());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitAddressInCaseRegularCustomerWithoutCompanyRelation(): void
    {
        // Arrange
        $restAddressTransfer = (new RestAddressBuilder())->build();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory $companyBusinessUnitAddressesRestApiBusinessFactoryMock */
        $companyBusinessUnitAddressesRestApiBusinessFactoryMock = $this->getMockBuilder(CompanyBusinessUnitAddressesRestApiBusinessFactory::class)
            ->onlyMethods(['getCompanyUnitAddressFacade'])
            ->getMock();

        $companyBusinessUnitAddressesRestApiBusinessFactoryMock
            ->method('getCompanyUnitAddressFacade')
            ->willReturn($this->getCompanyUnitAddressFacadeMock($this->getFakeCompanyUnitAddressResponseTransfer()));

        // Act
        $addressTransfer = $this->tester->getFacadeMock($companyBusinessUnitAddressesRestApiBusinessFactoryMock)
            ->getCompanyBusinessUnitAddress(
                $restAddressTransfer,
                (new QuoteBuilder())->withCustomer()->build()
            );

        // Assert
        $this->assertSame($restAddressTransfer->getAddress1(), $addressTransfer->getAddress1());
    }

    /**
     * @return void
     */
    public function testGetCompanyBusinessUnitAddressInCaseWhenCompanyUnitAddressWasNotFound(): void
    {
        // Arrange
        $restAddressTransfer = (new RestAddressBuilder())->build();

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory $companyBusinessUnitAddressesRestApiBusinessFactoryMock */
        $companyBusinessUnitAddressesRestApiBusinessFactoryMock = $this->getMockBuilder(CompanyBusinessUnitAddressesRestApiBusinessFactory::class)
            ->onlyMethods(['getCompanyUnitAddressFacade'])
            ->getMock();

        $companyBusinessUnitAddressesRestApiBusinessFactoryMock
            ->method('getCompanyUnitAddressFacade')
            ->willReturn($this->getCompanyUnitAddressFacadeMock());

        // Act
        $addressTransfer = $this->tester->getFacadeMock($companyBusinessUnitAddressesRestApiBusinessFactoryMock)
            ->getCompanyBusinessUnitAddress(
                $restAddressTransfer,
                (new QuoteBuilder())->withCustomer()->build()
            );

        // Assert
        $this->assertSame($restAddressTransfer->getAddress1(), $addressTransfer->getAddress1());
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return void
     */
    protected function assertCompanyBusinessUnitAddress(
        CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer,
        QuoteTransfer $quoteTransfer,
        AddressTransfer $addressTransfer
    ): void {
        $this->assertNull($addressTransfer->getUuid());
        $this->assertTrue($addressTransfer->getIsAddressSavingSkipped());
        $this->assertSame($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getAddress1(), $addressTransfer->getAddress1());
        $this->assertSame($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getAddress2(), $addressTransfer->getAddress2());
        $this->assertSame($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getPhone(), $addressTransfer->getPhone());
        $this->assertSame($quoteTransfer->getCustomer()->getEmail(), $addressTransfer->getEmail());
        $this->assertSame($quoteTransfer->getCustomer()->getFirstName(), $addressTransfer->getFirstName());
        $this->assertSame($quoteTransfer->getCustomer()->getLastName(), $addressTransfer->getLastName());
        $this->assertSame($companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getCompany()->getName(), $addressTransfer->getCompany());
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    protected function getFakeCompanyUnitAddressResponseTransfer(): CompanyUnitAddressResponseTransfer
    {
        $companyUnitAddressBuilder = (new CompanyUnitAddressBuilder())->withCompany()->build();
        $companyUnitAddressBuilder->getCompany()->setIdCompany(static::ID_COMPANY);

        return (new CompanyUnitAddressResponseTransfer())
            ->setIsSuccessful(true)
            ->setCompanyUnitAddressTransfer($companyUnitAddressBuilder);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer|null $companyUnitAddressResponseTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Dependency\Facade\CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacadeMock(
        ?CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer = null
    ): CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeInterface {
        $companyUnitAddressFacadeMock = $this->getMockBuilder(CompanyBusinessUnitAddressesRestApiToCompanyUnitAddressFacadeBridge::class)
            ->onlyMethods(['findCompanyBusinessUnitAddressByUuid'])
            ->disableOriginalConstructor()
            ->getMock();

        $companyUnitAddressFacadeMock
            ->method('findCompanyBusinessUnitAddressByUuid')
            ->willReturn($companyUnitAddressResponseTransfer ?? (new CompanyUnitAddressResponseTransfer())->setIsSuccessful(false));

        return $companyUnitAddressFacadeMock;
    }
}
