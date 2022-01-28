<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationshipApi;

use Codeception\Actor;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method \Spryker\Zed\MerchantRelationshipApi\Business\MerchantRelationshipApiFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantRelationshipApiBusinessTester extends Actor
{
    use _generated\MerchantRelationshipApiBusinessTesterActions;

    /**
     * @param array<string, mixed> $merchantData
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(array $merchantData = []): MerchantRelationshipTransfer
    {
        $merchantTransfer = $this->haveMerchant($merchantData);
        $companyBusinessUnitTransfer = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => 1,
        ]);

        return $this->haveMerchantRelationship([
            MerchantRelationshipTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer,
            MerchantRelationshipTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            MerchantRelationshipTransfer::MERCHANT_RELATIONSHIP_KEY => uniqid(),
        ]);
    }

    /**
     * @param int $requiredCount
     *
     * @return void
     */
    public function ensureRequiredNumberOdMerchantRelationshipsExist(int $requiredCount = 30): void
    {
        $count = $this->getMerchantRelationshipQuery()->count();
        if ($count >= $requiredCount) {
            return;
        }

        for ($i = 1; $i <= $requiredCount - $count; $i++) {
            $this->createMerchantRelationship();
        }
    }

    /**
     * @param array<string, string> $sort
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function createdSortingApiRequestTransfer(array $sort): ApiRequestTransfer
    {
        $apiFilterTransfer = (new ApiFilterTransfer())->setSort($sort);

        return (new ApiRequestTransfer())
            ->setFilter($apiFilterTransfer);
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    protected function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }
}
