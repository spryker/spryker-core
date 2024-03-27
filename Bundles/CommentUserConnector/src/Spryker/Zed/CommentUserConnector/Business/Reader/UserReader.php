<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentUserConnector\Business\Reader;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\CommentUserConnector\Dependency\Facade\CommentUserConnectorToUserFacadeInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @var \Spryker\Zed\CommentUserConnector\Dependency\Facade\CommentUserConnectorToUserFacadeInterface
     */
    protected CommentUserConnectorToUserFacadeInterface $userFacade;

    /**
     * @param \Spryker\Zed\CommentUserConnector\Dependency\Facade\CommentUserConnectorToUserFacadeInterface $userFacade
     */
    public function __construct(CommentUserConnectorToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findUserById(int $idUser): ?UserTransfer
    {
        $userCollectionTransfer = $this->getUserCollectionByUserIds([$idUser]);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param list<int> $userIds
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollectionByUserIds(array $userIds): UserCollectionTransfer
    {
        $userCriteriaTransfer = $this->createUserCriteriaTransfer($userIds);

        return $this->userFacade->getUserCollection($userCriteriaTransfer);
    }

    /**
     * @param list<int> $userIds
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(array $userIds): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->setUserIds($userIds);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }
}
