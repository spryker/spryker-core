<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;

class UserResourceRelationshipReader implements UserResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReaderInterface
     */
    protected UserResourceReaderInterface $userResourceReader;

    /**
     * @param \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceReaderInterface $userResourceReader
     */
    public function __construct(UserResourceReaderInterface $userResourceReader)
    {
        $this->userResourceReader = $userResourceReader;
    }

    /**
     * @param list<string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getUserRelationshipsIndexedByUserUuid(array $userUuids): array
    {
        $userResourceCollectionTransfer = $this->userResourceReader->getUsersResources(
            $this->createUserCriteriaTransfer($userUuids),
        );

        return $this->getGlueRelationshipTransfersIndexedByUserUuid($userResourceCollectionTransfer);
    }

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReader::getUserRelationshipsIndexedByUserUuid()} instead.
     *
     * @param list<string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getUserRelationshipsWithUsersRestAttributesIndexedByUserUuid(array $userUuids): array
    {
        $userResourceCollectionTransfer = $this->userResourceReader->getUserResourceCollection(
            $this->createUserCriteriaTransfer($userUuids),
        );

        return $this->getGlueRelationshipTransfersIndexedByUserUuid($userResourceCollectionTransfer);
    }

    /**
     * @param list<string> $userUuids
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(array $userUuids): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())->setUuids($userUuids);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserResourceCollectionTransfer $userResourceCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    protected function getGlueRelationshipTransfersIndexedByUserUuid(
        UserResourceCollectionTransfer $userResourceCollectionTransfer
    ): array {
        $indexedUserRelationshipTransfers = [];
        foreach ($userResourceCollectionTransfer->getUserResources() as $userResource) {
            $indexedUserRelationshipTransfers[$userResource->getIdOrFail()] = (new GlueRelationshipTransfer())->addResource($userResource);
        }

        return $indexedUserRelationshipTransfers;
    }
}
