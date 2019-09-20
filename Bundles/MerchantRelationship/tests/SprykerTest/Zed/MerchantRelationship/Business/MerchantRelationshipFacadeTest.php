<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Business\MerchantRelationship;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Business
 * @group MerchantRelationship
 * @group Facade
 * @group MerchantRelationshipFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRelationshipFacadeTest extends Unit
{
    protected const MR_KEY_TEST = 'mr-test';
    protected const BU_OWNER_KEY_OWNER = 'unit-owner';
    protected const BU_KEY_UNIT_1 = 'unit-1';
    protected const BU_KEY_UNIT_2 = 'unit-2';

    /**
     * @var \SprykerTest\Zed\MerchantRelationship\MerchantRelationshipBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateMerchantRelationship(): void
    {
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_TEST);

        // Assert
        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
    }

    /**
     * @expectedException \Exception
     *
     * @return void
     */
    public function testCreateMerchantRelationshipWithNotUniqueKeyThrowsException(): void
    {
        // Prepare
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(static::MR_KEY_TEST);
        $newMerchantRelationshipTransfer = clone $merchantRelationshipTransfer;
        $newMerchantRelationshipTransfer->setIdMerchantRelationship(null);

        // Action
        $this->tester->getFacade()
            ->createMerchantRelationship($newMerchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithOwner(): void
    {
        // Arrange
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_TEST, static::BU_OWNER_KEY_OWNER);

        // Assert
        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
        $this->assertSame(
            $merchantRelationship->getOwnerCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $merchantRelationship->getFkCompanyBusinessUnit()
        );
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithOneAssignee(): void
    {
        // Prepare
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
            CompanyBusinessUnitTransfer::KEY => static::BU_OWNER_KEY_OWNER,
        ]);
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())
            ->setMerchantRelationshipKey(static::MR_KEY_TEST)
            ->setOwnerCompanyBusinessUnit($companyBusinessUnitTransfer)
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setAssigneeCompanyBusinessUnits(
                (new CompanyBusinessUnitCollectionTransfer())
                    ->addCompanyBusinessUnit($companyBusinessUnitTransfer)
            );

        // Action
        $this->tester->getFacade()
            ->createMerchantRelationship($merchantRelationshipTransfer);

        // Assert
        $this->assertNotNull($merchantRelationshipTransfer->getIdMerchantRelationship());
        $this->assertNotNull($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits());
        $this->assertCount(1, $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits());
        $this->assertSame(static::BU_OWNER_KEY_OWNER, $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits()[0]->getKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithFewAssignee(): void
    {
        // Prepare
        $merchantRelationship = $this->tester->createMerchantRelationship(
            static::MR_KEY_TEST,
            static::BU_OWNER_KEY_OWNER,
            [static::BU_OWNER_KEY_OWNER, static::BU_KEY_UNIT_1, static::BU_KEY_UNIT_2]
        );

        // Assert
        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
        $this->assertNotNull($merchantRelationship->getAssigneeCompanyBusinessUnits());
        $this->assertCount(3, $merchantRelationship->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantRelationship(): void
    {
        // Prepare
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_TEST);
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        $newMerchant = $this->tester->haveMerchant();
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ]);
        $newKey = 'mr-test-1';

        $merchantRelationship
            ->setFkMerchant($newMerchant->getIdMerchant())
            ->setFkCompanyBusinessUnit($newCompanyBusinessUnit->getIdCompanyBusinessUnit())
            ->setMerchantRelationshipKey($newKey);

        // Action
        $updatedMerchantRelationship = $this->tester->getFacade()
            ->updateMerchantRelationship($merchantRelationship);

        // Assert
        $this->assertSame($idMerchantRelationship, $updatedMerchantRelationship->getIdMerchantRelationship());
        $this->assertSame($newMerchant->getIdMerchant(), $updatedMerchantRelationship->getFkMerchant());
        $this->assertSame($newCompanyBusinessUnit->getIdCompanyBusinessUnit(), $updatedMerchantRelationship->getFkCompanyBusinessUnit());
        $this->assertSame($newKey, $updatedMerchantRelationship->getMerchantRelationshipKey());
    }

    /**
     * @return void
     */
    public function testGetMerchantRelationshipById(): void
    {
        // Prepare
        $expectedMerchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_TEST);
        $expectedMerchantRelationship->setName(
            sprintf('%s - %s', $expectedMerchantRelationship->getIdMerchantRelationship(), $expectedMerchantRelationship->getOwnerCompanyBusinessUnit()->getName())
        );

        $merchantRelationship = (new MerchantRelationshipTransfer())
            ->setIdMerchantRelationship(
                $expectedMerchantRelationship->getIdMerchantRelationship()
            );

        // Act
        $actualMerchantRelationship = $this->tester->getFacade()
            ->getMerchantRelationshipById($merchantRelationship);

        // Assert
        $this->assertEquals($expectedMerchantRelationship->getIdMerchantRelationship(), $actualMerchantRelationship->getIdMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationship(): void
    {
        // Prepare
        $merchantRelationship = $this->tester->createMerchantRelationship(static::MR_KEY_TEST);
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        // Action
        $this->tester->getFacade()
            ->deleteMerchantRelationship($merchantRelationship);

        // Assert
        $this->tester->assertMerchantRelationshipNotExists($idMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipWithAssigneeDeletesAssignee(): void
    {
        // Prepare
        $merchantRelationship = $this->tester->createMerchantRelationship(
            static::MR_KEY_TEST,
            static::BU_OWNER_KEY_OWNER,
            [static::BU_OWNER_KEY_OWNER, static::BU_KEY_UNIT_1, static::BU_KEY_UNIT_2]
        );
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        // Action
        $this->tester->getFacade()
            ->deleteMerchantRelationship(
                (new MerchantRelationshipTransfer())
                    ->setIdMerchantRelationship($idMerchantRelationship)
            );

        // Assert
        $this->tester->assertMerchantRelationshipToCompanyBusinessUnitNotExists($idMerchantRelationship);
    }
}
