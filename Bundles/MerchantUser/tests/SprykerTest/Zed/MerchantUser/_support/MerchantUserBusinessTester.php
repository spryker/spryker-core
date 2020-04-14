<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantUserCriteriaTransfer;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUser;
use Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery;

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
 * @method \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantUserBusinessTester extends Actor
{
    use _generated\MerchantUserBusinessTesterActions;

    /**
     * Define custom actions here
     *
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser|null
     */
    public function findMerchantUser(MerchantUserCriteriaTransfer $merchantUserCriteriaTransfer): ?SpyMerchantUser
    {
        $query = $this->createMerchantUserPropelQuery();

        if ($merchantUserCriteriaTransfer->getIdMerchant()) {
            $query->filterByFkMerchant($merchantUserCriteriaTransfer->getIdMerchant());
        }

        if ($merchantUserCriteriaTransfer->getIdMerchantUser()) {
            $query->filterByIdMerchantUser($merchantUserCriteriaTransfer->getIdMerchantUser());
        }

        if ($merchantUserCriteriaTransfer->getIdUser()) {
            $query->filterByFkUser($merchantUserCriteriaTransfer->getIdUser());
        }

        return $query->findOne();
    }

    /**
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUserQuery
     */
    public function createMerchantUserPropelQuery(): SpyMerchantUserQuery
    {
        return SpyMerchantUserQuery::create();
    }
}
