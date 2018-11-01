<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantRelationshipProductListDataImport;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantRelationshipProductListDataImportCommunicationTester extends Actor
{
    use _generated\MerchantRelationshipProductListDataImportCommunicationTesterActions;

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

        $companyBusinessUnitSeed = $companyBusinessUnitOwnerKey ? [CompanyBusinessUnitTransfer::KEY => $companyBusinessUnitOwnerKey] : [];
        $companyBusinessUnitOwner = $this->haveCompanyBusinessUnit($companyBusinessUnitSeed);

        $assigneeCompanyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        if ($assigneeCompanyBusinessUnitKeys) {
            $companyBusinessUnits = new ArrayObject();
            foreach ($assigneeCompanyBusinessUnitKeys as $businessUnitKey) {
                if ($companyBusinessUnitOwnerKey === $businessUnitKey) {
                    $companyBusinessUnits->append($companyBusinessUnitOwner);
                    continue;
                }

                $companyBusinessUnit = $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::KEY => $businessUnitKey]);
                $companyBusinessUnits->append($companyBusinessUnit);
            }
            $assigneeCompanyBusinessUnitCollectionTransfer->setCompanyBusinessUnits($companyBusinessUnits);
        }

        return $this->haveMerchantRelationship([
            MerchantRelationshipTransfer::FK_MERCHANT => $merchant->getIdMerchant(),
            MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitOwner->getIdCompanyBusinessUnit(),
            MerchantRelationshipTransfer::MERCHANT_RELATIONSHIP_KEY => $merchantRelationshipKey,
            MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $companyBusinessUnitOwner,
            MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $assigneeCompanyBusinessUnitCollectionTransfer,
        ]);
    }
}
