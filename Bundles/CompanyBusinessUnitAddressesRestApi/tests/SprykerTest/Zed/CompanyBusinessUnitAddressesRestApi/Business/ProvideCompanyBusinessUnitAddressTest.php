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
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
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
 * @group ProvideCompanyBusinessUnitAddressTest
 * Add your own group annotations below this line
 */
class ProvideCompanyBusinessUnitAddressTest extends Unit
{
    protected const FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID = 'FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnitAddressesRestApi\CompanyBusinessUnitAddressesRestApiBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testProvideCompanyBusinessUnitAddressInCaseWhenAddressWasFound(): void
    {
        // Arrange
        $companyUnitAddressResponseTransfer = $this->getFakeCompanyUnitAddressResponseTransfer();
        $restAddressTransfer = (new RestAddressBuilder())->build()
            ->setIdCompanyBusinessUnitAddress(static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory $companyBusinessUnitAddressesRestApiBusinessFactoryMock */
        $companyBusinessUnitAddressesRestApiBusinessFactoryMock = $this->getMockBuilder(CompanyBusinessUnitAddressesRestApiBusinessFactory::class)
            ->onlyMethods(['getCompanyUnitAddressFacade'])
            ->getMock();

        $companyBusinessUnitAddressesRestApiBusinessFactoryMock
            ->method('getCompanyUnitAddressFacade')
            ->willReturn($this->getCompanyUnitAddressFacadeMock($companyUnitAddressResponseTransfer));

        // Act
        $addressTransfer = $this->tester->getFacadeMock($companyBusinessUnitAddressesRestApiBusinessFactoryMock)
            ->provideCompanyBusinessUnitAddress(
                $restAddressTransfer,
                (new QuoteBuilder())->withCustomer()->build()
            );

        // Assert
        $this->assertSame(
            $companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getAddress1(),
            $addressTransfer->getAddress1()
        );
    }

    /**
     * @return void
     */
    public function testProvideCompanyBusinessUnitAddressInCaseWhenCustomerAddressesWasNotFound(): void
    {
        // Arrange
        $restAddressTransfer = (new RestAddressBuilder())->build()
            ->setIdCompanyBusinessUnitAddress(static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID);

        /** @var \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\CompanyBusinessUnitAddressesRestApi\Business\CompanyBusinessUnitAddressesRestApiBusinessFactory $companyBusinessUnitAddressesRestApiBusinessFactoryMock */
        $companyBusinessUnitAddressesRestApiBusinessFactoryMock = $this->getMockBuilder(CompanyBusinessUnitAddressesRestApiBusinessFactory::class)
            ->onlyMethods(['getCompanyUnitAddressFacade'])
            ->getMock();

        $companyBusinessUnitAddressesRestApiBusinessFactoryMock
            ->method('getCompanyUnitAddressFacade')
            ->willReturn($this->getCompanyUnitAddressFacadeMock());

        // Act
        $addressTransfer = $this->tester->getFacadeMock($companyBusinessUnitAddressesRestApiBusinessFactoryMock)
            ->provideCompanyBusinessUnitAddress(
                $restAddressTransfer,
                (new QuoteBuilder())->withCustomer()->build()
            );

        // Assert
        $this->assertSame($restAddressTransfer->getAddress1(), $addressTransfer->getAddress1());
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    protected function getFakeCompanyUnitAddressResponseTransfer(): CompanyUnitAddressResponseTransfer
    {
        return (new CompanyUnitAddressResponseTransfer())
            ->setIsSuccessful(true)
            ->setCompanyUnitAddressTransfer((new CompanyUnitAddressBuilder())->withCompany()->build()
                ->setUuid(static::FAKE_COMPANY_BUSINESS_UNIT_ADDRESS_UUID));
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
