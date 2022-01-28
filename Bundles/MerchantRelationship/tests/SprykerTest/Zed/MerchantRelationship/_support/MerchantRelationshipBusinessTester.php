<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationship;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
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
 * @SuppressWarnings(PHPMD)
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
}
