<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;

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
     * @param array<int, string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getUserRelationshipsIndexedByUserUuid(array $userUuids): array
    {
        $indexedUserRelationshipTransfers = [];
        $userResourcesIndexedByUserUuid = $this->getUserResourcesIndexedByUserUuid($userUuids);

        foreach ($userResourcesIndexedByUserUuid as $userUuid => $userResource) {
            $indexedUserRelationshipTransfers[$userUuid] = (new GlueRelationshipTransfer())->addResource($userResource);
        }

        return $indexedUserRelationshipTransfers;
    }

    /**
     * @param list<string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function getUserResourcesIndexedByUserUuid(array $userUuids): array
    {
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->setUuids($userUuids);
        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions($userConditionsTransfer);

        $userResourceCollectionTransfer = $this->userResourceReader->getUserResourceCollection($userCriteriaTransfer);

        $indexedUserResources = [];
        foreach ($userResourceCollectionTransfer->getUserResources() as $userResource) {
            $indexedUserResources[$userResource->getIdOrFail()] = $userResource;
        }

        return $indexedUserResources;
    }
}
