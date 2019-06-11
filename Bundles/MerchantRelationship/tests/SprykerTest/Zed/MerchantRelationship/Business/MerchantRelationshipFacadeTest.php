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
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacade;
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface;

/**
 * Auto-generated group annotations
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
    /**
     * @var \MerchantRelationship\MerchantRelationshipBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateMerchantRelationship(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');

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
        $merchantRelationshipTransfer = $this->haveMerchantRelationship('mr-test');
        $newMerchantRelationshipTransfer = clone $merchantRelationshipTransfer;
        $newMerchantRelationshipTransfer->setIdMerchantRelationship(null);

        // Action
        $this->getFacade()->createMerchantRelationship($newMerchantRelationshipTransfer);
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithOwner(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test', 'unit-owner');

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
            CompanyBusinessUnitTransfer::KEY => 'unit-owner',
        ]);
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())
            ->setMerchantRelationshipKey('mr-test')
            ->setOwnerCompanyBusinessUnit($companyBusinessUnitTransfer)
            ->setFkCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setAssigneeCompanyBusinessUnits(
                (new CompanyBusinessUnitCollectionTransfer())
                    ->addCompanyBusinessUnit($companyBusinessUnitTransfer)
            );

        // Action
        $this->getFacade()->createMerchantRelationship($merchantRelationshipTransfer);

        // Assert
        $this->assertNotNull($merchantRelationshipTransfer->getIdMerchantRelationship());
        $this->assertNotNull($merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits());
        $this->assertCount(1, $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits());
        $this->assertSame('unit-owner', $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits()[0]->getKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithFewAssignee(): void
    {
        // Prepare
        $merchantRelationship = $this->haveMerchantRelationship(
            'mr-test',
            'unit-owner',
            ['unit-owner', 'unit-1', 'unit-2']
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
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');
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
        $updatedMerchantRelationship = $this->getFacade()
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
        $expectedMerchantRelationship = $this->haveMerchantRelationship('mr-test');
        $expectedMerchantRelationship->setName(
            sprintf('%s - %s', $expectedMerchantRelationship->getIdMerchantRelationship(), $expectedMerchantRelationship->getOwnerCompanyBusinessUnit()->getName())
        );

        $merchantRelationship = (new MerchantRelationshipTransfer())
            ->setIdMerchantRelationship(
                $expectedMerchantRelationship->getIdMerchantRelationship()
            );

        $actualMerchantRelationship = $this->getFacade()
            ->getMerchantRelationshipById($merchantRelationship);

        // Assert
        $this->assertNotNull($actualMerchantRelationship->getIdMerchantRelationship());
        $this->assertEquals($expectedMerchantRelationship->toArray(), $actualMerchantRelationship->toArray());
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationship(): void
    {
        // Prepare
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        // Action
        $this->getFacade()
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
        $merchantRelationship = $this->haveMerchantRelationship(
            'mr-test',
            'unit-owner',
            ['unit-owner', 'unit-1', 'unit-2']
        );
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        // Action
        $this->getFacade()->deleteMerchantRelationship(
            (new MerchantRelationshipTransfer())
                ->setIdMerchantRelationship($idMerchantRelationship)
        );

        // Assert
        $this->tester->assertMerchantRelationshipToCompanyBusinessUnitNotExists($idMerchantRelationship);
    }

    /**
     * @param string $merchantRelationshipKey
     * @param string|null $companyBusinessUnitOwnerKey
     * @param array $assigneeCompanyBusinessUnitKeys
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function haveMerchantRelationship(
        string $merchantRelationshipKey,
        ?string $companyBusinessUnitOwnerKey = null,
        array $assigneeCompanyBusinessUnitKeys = []
    ): MerchantRelationshipTransfer {
        $merchant = $this->tester->haveMerchant();

        $companyBusinessUnitSeed = [
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
        ];

        if ($companyBusinessUnitOwnerKey) {
            $companyBusinessUnitSeed[CompanyBusinessUnitTransfer::KEY] = $companyBusinessUnitOwnerKey;
        }

        $companyBusinessUnitOwner = $this->tester->haveCompanyBusinessUnit($companyBusinessUnitSeed);

        $assigneeCompanyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        if ($assigneeCompanyBusinessUnitKeys) {
            foreach ($assigneeCompanyBusinessUnitKeys as $businessUnitKey) {
                if ($companyBusinessUnitOwnerKey === $businessUnitKey) {
                    $assigneeCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitOwner);
                    continue;
                }

                $companyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
                    CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany()->getIdCompany(),
                    CompanyBusinessUnitTransfer::KEY => $businessUnitKey,
                ]);
                $assigneeCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnit);
            }
        }

        return $this->tester->haveMerchantRelationship([
            'fkMerchant' => $merchant->getIdMerchant(),
            'merchant' => $merchant,
            'fkCompanyBusinessUnit' => $companyBusinessUnitOwner->getIdCompanyBusinessUnit(),
            'merchantRelationshipKey' => $merchantRelationshipKey,
            'ownerCompanyBusinessUnit' => $companyBusinessUnitOwner,
            'assigneeCompanyBusinessUnits' => $assigneeCompanyBusinessUnitCollectionTransfer,
        ]);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface
     */
    protected function getFacade(): MerchantRelationshipFacadeInterface
    {
        return new MerchantRelationshipFacade();
    }
}
