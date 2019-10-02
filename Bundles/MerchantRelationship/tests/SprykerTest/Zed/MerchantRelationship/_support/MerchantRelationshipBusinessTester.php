<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationship;

use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Spryker\Zed\MerchantRelationship\Business\Expander\MerchantRelationshipExpander;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 * @method \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantRelationshipBusinessTester extends Actor
{
    use _generated\MerchantRelationshipBusinessTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param string $merchantRelationshipKey
     * @param string|null $companyBusinessUnitOwnerKey
     * @param array $assigneeCompanyBusinessUnitKeys
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(
        string $merchantRelationshipKey,
        ?string $companyBusinessUnitOwnerKey = null,
        array $assigneeCompanyBusinessUnitKeys = []
    ): MerchantRelationshipTransfer {
        $merchant = $this->haveMerchant();

        $companyBusinessUnitSeed = [
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany(),
        ];

        if ($companyBusinessUnitOwnerKey) {
            $companyBusinessUnitSeed[CompanyBusinessUnitTransfer::KEY] = $companyBusinessUnitOwnerKey;
        }

        $companyBusinessUnitOwner = $this->haveCompanyBusinessUnit($companyBusinessUnitSeed);

        $assigneeCompanyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        if ($assigneeCompanyBusinessUnitKeys) {
            foreach ($assigneeCompanyBusinessUnitKeys as $businessUnitKey) {
                if ($companyBusinessUnitOwnerKey === $businessUnitKey) {
                    $assigneeCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitOwner);
                    continue;
                }

                $companyBusinessUnit = $this->haveCompanyBusinessUnit([
                    CompanyBusinessUnitTransfer::FK_COMPANY => $this->haveCompany()->getIdCompany(),
                    CompanyBusinessUnitTransfer::KEY => $businessUnitKey,
                ]);
                $assigneeCompanyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnit);
            }
        }

        return $this->haveMerchantRelationship([
            'fkMerchant' => $merchant->getIdMerchant(),
            'merchant' => $merchant,
            'fkCompanyBusinessUnit' => $companyBusinessUnitOwner->getIdCompanyBusinessUnit(),
            'merchantRelationshipKey' => $merchantRelationshipKey,
            'ownerCompanyBusinessUnit' => $companyBusinessUnitOwner,
            'assigneeCompanyBusinessUnits' => $assigneeCompanyBusinessUnitCollectionTransfer,
        ]);
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assertMerchantRelationshipNotExists(int $idMerchantRelationship): void
    {
        $merchantRelationshipQuery = $this->getMerchantRelationshipQuery()
            ->filterByIdMerchantRelationship($idMerchantRelationship);

        $this->assertSame(0, $merchantRelationshipQuery->count());
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function assertMerchantRelationshipToCompanyBusinessUnitNotExists(int $idMerchantRelationship): void
    {
        $merchantRelationshipToCompanyBusinessUnitQuery = $this->getMerchantRelationshipToCompanyBusinessUnitQuery()
            ->filterByFkMerchantRelationship($idMerchantRelationship);

        $this->assertSame(0, $merchantRelationshipToCompanyBusinessUnitQuery->count());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expandMecrhantRelationshipWithName(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return (new MerchantRelationshipExpander())->expandWithName($merchantRelationshipTransfer);
    }

    /**
     * @return int
     */
    public function getMerchantRelationsCount(): int
    {
        return SpyMerchantRelationshipQuery::create()->count();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery
     */
    protected function getMerchantRelationshipToCompanyBusinessUnitQuery(): SpyMerchantRelationshipToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
    }
}
