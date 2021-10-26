<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacade;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyBusinessUnit
 * @group Business
 * @group CompanyBusinessUnitFacade
 * @group UpdateTest
 * Add your own group annotations below this line
 */
class UpdateTest extends Test
{
    /**
     * @var string
     */
    protected const INITIAL_NAME = 'Initial Name';

    /**
     * @var string
     */
    protected const FINAL_NAME = 'Final Name';

    /**
     * @var string
     *
     * @uses \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriter::ERROR_MESSAGE_HIERARCHY_CYCLE_IN_BUSINESS_UNIT_UPDATE
     */
    protected const ERROR_MESSAGE_HIERARCHY_CYCLE_IN_BUSINESS_UNIT_UPDATE = 'message.business_unit.update.cycle_dependency_error';

    /**
     * @var \SprykerTest\Zed\CompanyBusinessUnit\CompanyBusinessUnitTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateSuccessful(): void
    {
        // Arrange
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $headCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
        ]);

        $regionalCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $headCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $localCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::INITIAL_NAME,
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $headCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $localCompanyBusinessUnitTransfer->setName(static::FINAL_NAME);
        $localCompanyBusinessUnitTransfer->setFkParentCompanyBusinessUnit($regionalCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail());

        // Act
        $companyBusinessUnitResponseTransfer = $this->tester
            ->getFacade()
            ->update($localCompanyBusinessUnitTransfer);

        // Assert
        $this->assertTrue($companyBusinessUnitResponseTransfer->getIsSuccessfulOrFail());
        $this->assertSame(
            static::FINAL_NAME,
            $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransferOrFail()->getNameOrFail(),
        );
        $this->assertSame(
            $regionalCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
            $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransferOrFail()->getFkParentCompanyBusinessUnit(),
        );
    }

    /**
     * @return void
     */
    public function testUpdateWithCycleInHierarchy(): void
    {
        // Arrange
        $idCompany = $this->tester->haveCompany()->getIdCompany();

        $headCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
        ]);

        $regionalCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $headCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $localCompanyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => static::INITIAL_NAME,
            CompanyBusinessUnitTransfer::FK_COMPANY => $idCompany,
            CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT => $regionalCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
        ]);

        $headCompanyBusinessUnitTransfer->setFkParentCompanyBusinessUnit($localCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail());

        // Act
        $companyBusinessUnitResponseTransfer = $this->tester
            ->getFacade()
            ->update($headCompanyBusinessUnitTransfer);

        // Assert
        $this->assertFalse($companyBusinessUnitResponseTransfer->getIsSuccessfulOrFail());
        $this->assertSame(
            static::ERROR_MESSAGE_HIERARCHY_CYCLE_IN_BUSINESS_UNIT_UPDATE,
            $companyBusinessUnitResponseTransfer->getMessages()[0]->getText(),
        );
    }
}
