<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\DataBuilder\MerchantRelationshipBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUnitAddress
 * @group Business
 * @group Facade
 * @group ExpandMerchantRelationshipCollectionWithCompanyUnitAddressTest
 * Add your own group annotations below this line
 */
class ExpandMerchantRelationshipCollectionWithCompanyUnitAddressTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUnitAddress\CompanyUnitAddressBusinessTester
     */
    protected CompanyUnitAddressBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandsCollectionWithCorrespondingCompanyUnitAddresses(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnit1Transfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressesCollection(2),
        ]);
        $companyBusinessUnit2Transfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressesCollection(1),
        ]);

        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnit1Transfer);
        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnit2Transfer);

        $companyBusinessUnitCollectionTransfer = (new CompanyBusinessUnitCollectionTransfer())
            ->addCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($companyBusinessUnit1Transfer->getIdCompanyBusinessUnitOrFail()),
            );
        $merchantRelationshipTransfer = (new MerchantRelationshipBuilder([
            MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $companyBusinessUnitCollectionTransfer,
        ]))->build();
        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantRelationshipCollectionWithCompanyUnitAddress($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());

        $merchantRelationshipTransfer = $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current();
        $this->assertNotNull($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits());
        $this->assertCount(1, $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits());

        $resultCompanyBusinessUnitTransfer = $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()
            ->getCompanyBusinessUnits()
            ->getIterator()
            ->current();
        $this->assertNotNull($resultCompanyBusinessUnitTransfer->getAddressCollection());
        $this->tester->assertCompanyUnitAddressCollection(
            $companyBusinessUnit1Transfer->getAddressCollectionOrFail(),
            $resultCompanyBusinessUnitTransfer->getAddressCollectionOrFail(),
        );
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenCompanyBusinessUnitDoNotHaveAddresses(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $companyBusinessUnit1Transfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
        ]);
        $companyBusinessUnit2Transfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
            CompanyBusinessUnitTransfer::ADDRESS_COLLECTION => $this->tester->createCompanyUnitAddressesCollection(1),
        ]);

        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnit1Transfer);
        $this->tester->getFacade()->saveCompanyBusinessUnitAddresses($companyBusinessUnit2Transfer);

        $companyBusinessUnitCollectionTransfer = (new CompanyBusinessUnitCollectionTransfer())
            ->addCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($companyBusinessUnit1Transfer->getIdCompanyBusinessUnitOrFail()),
            );
        $merchantRelationshipTransfer = (new MerchantRelationshipBuilder([
            MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $companyBusinessUnitCollectionTransfer,
        ]))->build();
        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantRelationshipCollectionWithCompanyUnitAddress($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationshipCollectionTransfer->getMerchantRelationships());

        $merchantRelationshipTransfer = $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current();
        $this->assertNotNull($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits());
        $this->assertCount(1, $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits());

        $resultCompanyBusinessUnitTransfer = $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()
            ->getCompanyBusinessUnits()
            ->getIterator()
            ->current();
        $this->assertNotNull($resultCompanyBusinessUnitTransfer->getAddressCollection());
        $this->assertCount(0, $resultCompanyBusinessUnitTransfer->getAddressCollectionOrFail()->getCompanyUnitAddresses());
    }

    /**
     * @dataProvider throwsExceptionWhenRequiredTransferPropertyIsNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return void
     */
    public function testThrowsExceptionWhenRequiredTransferPropertyIsNotSet(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): void {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()
            ->expandMerchantRelationshipCollectionWithCompanyUnitAddress($merchantRelationshipCollectionTransfer);
    }

    /**
     * @return array
     */
    protected function throwsExceptionWhenRequiredTransferPropertyIsNotSetDataProvider(): array
    {
        return [
            '`assigneeCompanyBusinessUnits` is not set.' => [
                (new MerchantRelationshipCollectionTransfer())->addMerchantRelationship(
                    (new MerchantRelationshipBuilder([
                        MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => null,
                    ]))->build(),
                ),
            ],
            '`idCompanyBusinessUnit` is not set.' => [
                (new MerchantRelationshipCollectionTransfer())->addMerchantRelationship(
                    (new MerchantRelationshipBuilder([
                        MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => (new CompanyBusinessUnitCollectionTransfer())
                            ->addCompanyBusinessUnit((new CompanyBusinessUnitBuilder([
                                CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => null,
                            ]))->build()),
                    ]))->build(),
                ),
            ],
        ];
    }
}
