<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Reader;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToUserFacadeInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @param \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToUserFacadeInterface $userFacade
     */
    public function __construct(protected DataImportMerchantToUserFacadeInterface $userFacade)
    {
    }

    /**
     * @param list<int> $userIds
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollectionByUserIds(array $userIds): UserCollectionTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->setUserIds($userIds);

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions($userConditionsTransfer);

        return $this->userFacade->getUserCollection($userCriteriaTransfer);
    }
}
