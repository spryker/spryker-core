<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantUser;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer;
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
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantUserTransfer|null
     */

    /**
     * @param \Generated\Shared\Transfer\MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer
     *
     * @return \Orm\Zed\MerchantUser\Persistence\SpyMerchantUser|null
     */
    public function findMerchantUser(MerchantUserCriteriaFilterTransfer $merchantUserCriteriaFilterTransfer): ?SpyMerchantUser
    {
        $query = $this->createMerchantUserPropelQuery();

        if ($merchantUserCriteriaFilterTransfer->getIdMerchant()) {
            $query->filterByFkMerchant($merchantUserCriteriaFilterTransfer->getIdMerchant());
        }

        if ($merchantUserCriteriaFilterTransfer->getIdUser()) {
            $query->filterByFkUser($merchantUserCriteriaFilterTransfer->getIdUser());
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
