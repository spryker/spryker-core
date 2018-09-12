<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Business\MerchantRelationship;

use ArrayObject;
use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacade;

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

        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithNotUniqueKeyThrowsException(): void
    {
        $this->haveMerchantRelationship('mr-test');

        $this->expectException(Exception::class);

        $this->haveMerchantRelationship('mr-test');
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithOwner(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test', 'unit-owner');

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
        $merchantRelationship = $this->haveMerchantRelationship(
            'mr-test',
            'unit-owner',
            ['unit-owner']
        );

        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
        $this->assertNotNull($merchantRelationship->getAssigneeCompanyBusinessUnits());
        $this->assertCount(1, $merchantRelationship->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits());
        $this->assertSame('unit-owner', $merchantRelationship->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits()[0]->getKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantRelationshipWithFewAssignee(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship(
            'mr-test',
            'unit-owner',
            ['unit-owner', 'unit-1', 'unit-2']
        );

        $this->assertNotNull($merchantRelationship->getIdMerchantRelationship());
        $this->assertNotNull($merchantRelationship->getAssigneeCompanyBusinessUnits());
        $this->assertCount(3, $merchantRelationship->getAssigneeCompanyBusinessUnits()->getCompanyBusinessUnits());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantRelationship(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        $newMerchant = $this->tester->haveMerchant();
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit();
        $newKey = 'mr-test-1';

        $merchantRelationship
            ->setFkMerchant($newMerchant->getIdMerchant())
            ->setFkCompanyBusinessUnit($newCompanyBusinessUnit->getIdCompanyBusinessUnit())
            ->setMerchantRelationshipKey($newKey);

        $updatedMerchantRelationship = (new MerchantRelationshipFacade())
            ->updateMerchantRelationship($merchantRelationship);

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
        $expectedMerchantRelationship = $this->haveMerchantRelationship('mr-test');

        $merchantRelationship = (new MerchantRelationshipTransfer())->setIdMerchantRelationship(
            $expectedMerchantRelationship->getIdMerchantRelationship()
        );

        $actualMerchantRelationship = (new MerchantRelationshipFacade())
            ->getMerchantRelationshipById($merchantRelationship);

        $this->assertNotNull($actualMerchantRelationship->getIdMerchantRelationship());
        $this->assertEquals($expectedMerchantRelationship->toArray(), $actualMerchantRelationship->toArray());
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationship(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        (new MerchantRelationshipFacade())
            ->deleteMerchantRelationship($merchantRelationship);

        $this->tester->assertMerchantRelationshipNotExists($idMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipWithAssigneeDeletesAssignee(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship(
            'mr-test',
            'unit-owner',
            ['unit-owner', 'unit-1', 'unit-2']
        );
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        (new MerchantRelationshipFacade())->deleteMerchantRelationship(
            (new MerchantRelationshipTransfer())
                ->setIdMerchantRelationship($idMerchantRelationship)
        );

        $this->tester->assertMerchantRelationshipToCompanyBusinessUnitNotExists($idMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipWithPreCheck(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship('mr-test');
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        $merchantRelationshipDeleteResponseTransfer = (new MerchantRelationshipFacade())
            ->deleteMerchantRelationshipWithPreCheck($merchantRelationship);

        $this->tester->assertTrue($merchantRelationshipDeleteResponseTransfer->getIsSuccess());
        $this->tester->assertMerchantRelationshipNotExists($idMerchantRelationship);
    }

    /**
     * @return void
     */
    public function testDeleteMerchantRelationshipWithPreCheckAndAssigneeDeletesAssignee(): void
    {
        $merchantRelationship = $this->haveMerchantRelationship(
            'mr-test',
            'unit-owner',
            ['unit-owner', 'unit-1', 'unit-2']
        );
        $idMerchantRelationship = $merchantRelationship->getIdMerchantRelationship();

        $merchantRelationshipDeleteResponseTransfer = (new MerchantRelationshipFacade())->deleteMerchantRelationshipWithPreCheck(
            (new MerchantRelationshipTransfer())
                ->setIdMerchantRelationship($idMerchantRelationship)
        );

        $this->tester->assertTrue($merchantRelationshipDeleteResponseTransfer->getIsSuccess());
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

        $companyBusinessUnitSeed = $companyBusinessUnitOwnerKey ? ['key' => $companyBusinessUnitOwnerKey] : [];
        $companyBusinessUnitOwner = $this->tester->haveCompanyBusinessUnit($companyBusinessUnitSeed);

        $assigneeCompanyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        if ($assigneeCompanyBusinessUnitKeys) {
            $companyBusinessUnits = new ArrayObject();
            foreach ($assigneeCompanyBusinessUnitKeys as $businessUnitKey) {
                if ($companyBusinessUnitOwnerKey === $businessUnitKey) {
                    $companyBusinessUnits->append($companyBusinessUnitOwner);
                    continue;
                }

                $companyBusinessUnit = $this->tester->haveCompanyBusinessUnit(['key' => $businessUnitKey]);
                $companyBusinessUnits->append($companyBusinessUnit);
            }
            $assigneeCompanyBusinessUnitCollectionTransfer->setCompanyBusinessUnits($companyBusinessUnits);
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
}
