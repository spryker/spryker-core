<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationship;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\MerchantRelationship\PHPMD)
 */
class MerchantRelationshipBusinessTester extends Actor
{
    use _generated\MerchantRelationshipBusinessTesterActions;

    /**
     * @param string $merchantRelationshipKey
     * @param string|null $companyBusinessUnitOwnerKey
     * @param array<string> $assigneeCompanyBusinessUnitKeys
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipRequestTransfer
     */
    public function createMerchantRelationshipRequest(
        string $merchantRelationshipKey,
        ?string $companyBusinessUnitOwnerKey = null,
        array $assigneeCompanyBusinessUnitKeys = []
    ): MerchantRelationshipRequestTransfer {
        $merchantRelationship = $this->createMerchantRelationship(
            $merchantRelationshipKey,
            $companyBusinessUnitOwnerKey,
            $assigneeCompanyBusinessUnitKeys,
        );

        $merchantRelationshipRequestTransfer = new MerchantRelationshipRequestTransfer();
        $merchantRelationshipRequestTransfer->setMerchantRelationship($merchantRelationship);

        return $merchantRelationshipRequestTransfer;
    }

    /**
     * @param string $merchantRelationshipKey
     * @param string|null $companyBusinessUnitOwnerKey
     * @param array<string> $assigneeCompanyBusinessUnitKeys
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(
        string $merchantRelationshipKey,
        ?string $companyBusinessUnitOwnerKey = null,
        array $assigneeCompanyBusinessUnitKeys = []
    ): MerchantRelationshipTransfer {
        $merchantTransfer = $this->haveMerchant();
        $companyTransfer = $this->haveCompany();

        $companyBusinessUnitSeed = [
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ];

        if ($companyBusinessUnitOwnerKey) {
            $companyBusinessUnitSeed[CompanyBusinessUnitTransfer::KEY] = $companyBusinessUnitOwnerKey;
        }

        $ownerCompanyBusinessUnitTransfer = $this->haveCompanyBusinessUnit($companyBusinessUnitSeed);

        $assigneeCompanyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        if ($assigneeCompanyBusinessUnitKeys) {
            foreach ($assigneeCompanyBusinessUnitKeys as $businessUnitKey) {
                if ($companyBusinessUnitOwnerKey === $businessUnitKey) {
                    $assigneeCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($ownerCompanyBusinessUnitTransfer);

                    continue;
                }

                $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit([
                    CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                    CompanyBusinessUnitTransfer::KEY => $businessUnitKey,
                ]);
                $assigneeCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);
            }
        }

        return $this->haveMerchantRelationship([
            MerchantRelationshipTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantRelationshipTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            MerchantRelationshipTransfer::MERCHANT_RELATIONSHIP_KEY => $merchantRelationshipKey,
            MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnitTransfer,
            MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $assigneeCompanyBusinessUnitCollectionTransfer,
        ]);
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assertMerchantRelationshipDoesNotExist(int $idMerchantRelationship): void
    {
        $merchantRelationshipQuery = $this->getMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($idMerchantRelationship);

        $this->assertSame(0, $merchantRelationshipQuery->count());
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assertMerchantRelationshipToCompanyBusinessUnitDoesNotExist(int $idMerchantRelationship): void
    {
        $merchantRelationshipToCompanyBusinessUnitQuery = $this->getMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship);

        $this->assertSame(0, $merchantRelationshipToCompanyBusinessUnitQuery->count());
    }

    /**
     * @return int
     */
    public function getMerchantRelationsCount(): int
    {
        return SpyMerchantRelationshipQuery::create()->count();
    }

    /**
     * @param int $amount
     * @param string $direction
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function createMerchantRelationshipsForSorting(int $amount, string $direction = 'ASC'): array
    {
        $merchantRelationships = [];
        for ($i = 1; $i <= $amount; $i++) {
            $merchantTransfer = $this->haveMerchant([
                MerchantTransfer::NAME => $direction === 'ASC' ? 'AAA-' . $i : 'ZZZ-' . $i,
            ]);
            $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit([
                CompanyBusinessUnitTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany(),
            ]);
            $merchantRelationships[] = $this->haveMerchantRelationship([
                MerchantRelationshipTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
                MerchantRelationshipTransfer::MERCHANT => $merchantTransfer,
                MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
                MerchantRelationshipTransfer::MERCHANT_RELATIONSHIP_KEY => $direction === 'ASC' ? 'AAA-' . $i : 'ZZZ-' . $i,
                MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
            ]);
        }

        return $merchantRelationships;
    }

    /**
     * @param int $amount
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function createMerchantRelationships(int $amount): array
    {
        $merchantRelationships = [];
        for ($i = 1; $i <= $amount; $i++) {
            $merchantRelationships[] = $this->createMerchantRelationship(uniqid('MR-'));
        }

        return $merchantRelationships;
    }

    /**
     * @param array<mixed> $merchantRelationshipSeedData
     * @param array<mixed> $companySeedData
     * @param array<mixed> $ownerCompanyBusinessUnitSeedData
     * @param array<mixed> $assigneeCompanyBusinessUnitSeedData
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationshipBySeedData(
        array $merchantRelationshipSeedData = [],
        array $companySeedData = [],
        array $ownerCompanyBusinessUnitSeedData = [],
        array $assigneeCompanyBusinessUnitSeedData = []
    ): MerchantRelationshipTransfer {
        $merchantTransfer = $this->haveMerchant();
        $companyTransfer = $this->haveCompany(array_merge([CompanyTransfer::IS_ACTIVE => true], $companySeedData));

        $ownerCompanyBusinessUnitTransfer = $this->haveCompanyBusinessUnit(array_merge([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ], $ownerCompanyBusinessUnitSeedData));
        $assigneeCompanyBusinessUnitTransfer = $this->haveCompanyBusinessUnit(array_merge([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ], $assigneeCompanyBusinessUnitSeedData));
        $assigneeCompanyBusinessUnitCollectionTransfer = (new CompanyBusinessUnitCollectionTransfer())
            ->addCompanyBusinessUnit($assigneeCompanyBusinessUnitTransfer);

        return $this->haveMerchantRelationship(array_merge([
            MerchantRelationshipTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
            MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail(),
            MerchantRelationshipTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnitTransfer,
            MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $assigneeCompanyBusinessUnitCollectionTransfer,
        ], $merchantRelationshipSeedData));
    }

    /**
     * @return void
     */
    public function ensureMerchantRelationshipTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantRelationshipQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return void
     */
    public function assertCollectionContainsMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): void {
        $actualMerchantRelationshipTransfer = $this->findMerchantRelationshipByIdMerchantRelationship(
            $merchantRelationshipCollectionTransfer,
            $merchantRelationshipTransfer->getIdMerchantRelationshipOrFail(),
        );

        $this->assertNotNull($actualMerchantRelationshipTransfer);
        $this->assertSameMerchantRelationship($merchantRelationshipTransfer, $actualMerchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return void
     */
    public function assertCollectionDoesNotContainMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): void {
        $actualMerchantRelationshipTransfer = $this->findMerchantRelationshipByIdMerchantRelationship(
            $merchantRelationshipCollectionTransfer,
            $merchantRelationshipTransfer->getIdMerchantRelationshipOrFail(),
        );

        $this->assertNull($actualMerchantRelationshipTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $expectedMerchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $actualMerchantRelationshipTransfer
     *
     * @return void
     */
    public function assertSameMerchantRelationship(
        MerchantRelationshipTransfer $expectedMerchantRelationshipTransfer,
        MerchantRelationshipTransfer $actualMerchantRelationshipTransfer
    ): void {
        $this->assertSame(
            $expectedMerchantRelationshipTransfer->getIdMerchantRelationshipOrFail(),
            $actualMerchantRelationshipTransfer->getIdMerchantRelationship(),
        );

        $this->assertNotNull($actualMerchantRelationshipTransfer->getOwnerCompanyBusinessUnit());
        $this->assertSame(
            $expectedMerchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnitOrFail(),
            $actualMerchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getIdCompanyBusinessUnit(),
        );

        $this->assertNotNull($actualMerchantRelationshipTransfer->getAssigneeCompanyBusinessUnits());
        $this->assertSame(
            $expectedMerchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits()->count(),
            $actualMerchantRelationshipTransfer->getAssigneeCompanyBusinessUnitsOrFail()->getCompanyBusinessUnits()->count(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    protected function findMerchantRelationshipByIdMerchantRelationship(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer,
        int $idMerchantRelationship
    ): ?MerchantRelationshipTransfer {
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            if ($merchantRelationshipTransfer->getIdMerchantRelationship() === $idMerchantRelationship) {
                return $merchantRelationshipTransfer;
            }
        }

        return null;
    }

    /**
     * @param int $idMerchant
     *
     * @return void
     */
    public function deactivateMerchant(int $idMerchant): void
    {
        $merchantEntity = $this->getMerchantQuery()
            ->filterByIdMerchant($idMerchant)
            ->findOne();

        $merchantEntity->setIsActive(false);
        $merchantEntity->save();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery
     */
    protected function getMerchantRelationshipToCompanyBusinessUnitQuery(): SpyMerchantRelationshipToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchantQuery
     */
    protected function getMerchantQuery(): SpyMerchantQuery
    {
        return SpyMerchantQuery::create();
    }
}
