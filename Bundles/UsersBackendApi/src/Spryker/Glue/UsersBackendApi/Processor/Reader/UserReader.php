<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @var \Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface
     */
    protected UsersBackendApiToUserFacadeInterface $userFacade;

    /**
     * @param \Spryker\Glue\UsersBackendApi\Dependency\Facade\UsersBackendApiToUserFacadeInterface $userFacade
     */
    public function __construct(UsersBackendApiToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param array<string> $userUuids
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollectionTransferByUuids(array $userUuids): UserCollectionTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->setUuids($userUuids);
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        return $this->userFacade->getUserCollection($userCriteriaTransfer);
    }
}
