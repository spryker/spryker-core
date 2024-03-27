<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface;
use SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationRequest
 * @group Business
 * @group Facade
 * @group DeleteCompanyUserMerchantRelationRequestsTest
 * Add your own group annotations below this line
 */
class DeleteCompanyUserMerchantRelationRequestsTest extends Unit
{
    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @var \SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester
     */
    protected MerchantRelationRequestBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureMerchantRelationRequestTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testRequiresIdCompanyUserToBeSet(): void
    {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage('Property "idCompanyUser" of transfer `Generated\Shared\Transfer\CompanyUserTransfer` is null.');

        // Act
        $this->tester->getFacade()->deleteCompanyUserMerchantRelationRequests(new CompanyUserTransfer());
    }

    /**
     * @dataProvider testDeletesMerchantRelationRequestsDataProvider
     *
     * @param bool $withAssigneeCompanyBusinessUnits
     *
     * @return void
     */
    public function testDeletesMerchantRelationRequests(bool $withAssigneeCompanyBusinessUnits): void
    {
        // Arrange
        $merchantRelationRequestTransfer1 = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequestTransfer2 = $this->tester->createStatusSpecificRequest(
            static::STATUS_PENDING,
            [],
            [],
            [],
            $withAssigneeCompanyBusinessUnits,
        );
        $companyUserTransfer = (new CompanyUserTransfer())->setIdCompanyUser(
            $merchantRelationRequestTransfer2->getCompanyUserOrFail()->getIdCompanyUser(),
        );

        // Act
        $this->tester->getFacade()->deleteCompanyUserMerchantRelationRequests($companyUserTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->count());
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()
            ->filterByIdMerchantRelationRequest($merchantRelationRequestTransfer1->getIdMerchantRelationRequestOrFail())
            ->count());
        $this->assertSame(2, $this->tester->getMerchantRelationRequestToCompanyBusinessUnitQuery()->count());
    }

    /**
     * @group test2
     *
     * @return void
     */
    public function testDeletesByChunks(): void
    {
        // Arrange
        $this->tester->mockConfigMethod('getReadMerchantRelationRequestCollectionBatchSize', 1);

        $merchantRelationRequestEntityManagerMock = $this->getMerchantRelationRequestEntityManagerMock();
        $this->tester->mockFactoryMethod('getEntityManager', $merchantRelationRequestEntityManagerMock);

        $merchantTransfer = $this->tester->haveMerchant();
        $companyTransfer = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $this->tester->haveMerchantRelationRequest([
            MerchantRelationRequestTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationRequestTransfer::COMPANY_USER => $companyUserTransfer,
            MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);
        $this->tester->haveMerchantRelationRequest([
            MerchantRelationRequestTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationRequestTransfer::COMPANY_USER => $companyUserTransfer,
            MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
        ]);

        $companyUserTransfer = (new CompanyUserTransfer())->setIdCompanyUser(
            $companyUserTransfer->getIdCompanyUserOrFail(),
        );

        // Assert
        $merchantRelationRequestEntityManagerMock->expects($this->exactly(2))
            ->method('deleteMerchantRelationRequestCollection');

        // Act
        $this->tester->getFacade()->deleteCompanyUserMerchantRelationRequests($companyUserTransfer);
    }

    /**
     * @return array<string, list<bool>>
     */
    public function testDeletesMerchantRelationRequestsDataProvider(): array
    {
        return [
            'With assignee company business units' => [true],
            'Without assignee company business units' => [false],
        ];
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\MerchantRelationRequest\Persistence\MerchantRelationRequestEntityManagerInterface
     */
    protected function getMerchantRelationRequestEntityManagerMock(): MerchantRelationRequestEntityManagerInterface
    {
        return $this->getMockBuilder(MerchantRelationRequestEntityManagerInterface::class)
            ->getMock();
    }
}
