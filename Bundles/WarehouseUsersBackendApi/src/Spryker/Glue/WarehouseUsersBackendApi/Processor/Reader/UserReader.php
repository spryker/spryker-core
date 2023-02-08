<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToUserFacadeInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @var \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToUserFacadeInterface
     */
    protected WarehouseUsersBackendApiToUserFacadeInterface $userFacade;

    /**
     * @param \Spryker\Glue\WarehouseUsersBackendApi\Dependency\Facade\WarehouseUsersBackendApiToUserFacadeInterface $userFacade
     */
    public function __construct(WarehouseUsersBackendApiToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserTransferById(int $idUser): ?UserTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->addIdUser($idUser);
        $userCriteriaTransfer = (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);

        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }
}
