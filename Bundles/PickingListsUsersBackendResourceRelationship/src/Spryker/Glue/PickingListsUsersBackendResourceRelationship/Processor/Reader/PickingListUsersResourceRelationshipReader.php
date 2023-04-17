<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Reader;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Facade\PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Resource\PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface;

class PickingListUsersResourceRelationshipReader implements PickingListUsersResourceRelationshipReaderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Facade\PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface
     */
    protected PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface $pickingListFacade;

    /**
     * @var \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Resource\PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface
     */
    protected PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface $usersBackendApiResource;

    /**
     * @param \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Facade\PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface $pickingListFacade
     * @param \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Dependency\Resource\PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface $usersBackendApiResource
     */
    public function __construct(
        PickingListsUsersBackendResourceRelationshipToPickingListFacadeInterface $pickingListFacade,
        PickingListsUsersBackendResourceRelationshipToUsersBackendApiResourceInterface $usersBackendApiResource
    ) {
        $this->pickingListFacade = $pickingListFacade;
        $this->usersBackendApiResource = $usersBackendApiResource;
    }

    /**
     * @param list<string> $pickingListUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer>
     */
    public function getUserRelationshipsIndexedByPickingListUuid(array $pickingListUuids): array
    {
        $indexedUserRelationshipTransfers = [];
        $pickingListCollectionTransfer = $this->getPickingListCollectionByPickingListUuids($pickingListUuids);
        $userUuidsIndexedByPickingListUuids = $this->getUserUuidsIndexedByPickingListUuid($pickingListCollectionTransfer);

        $userUuids = array_unique(array_values($userUuidsIndexedByPickingListUuids));
        $userResourcesIndexedByUuid = $this->getUserResourcesIndexedByUuid($userUuids);

        foreach ($userUuidsIndexedByPickingListUuids as $pickingListUuid => $userUuid) {
            $userResource = $userResourcesIndexedByUuid[$userUuid] ?? null;

            if (!$userResource) {
                continue;
            }

            $indexedUserRelationshipTransfers[$pickingListUuid] = (new GlueRelationshipTransfer())->addResource($userResource);
        }

        return $indexedUserRelationshipTransfers;
    }

    /**
     * @param list<string> $pickingListUuids
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function getPickingListCollectionByPickingListUuids(array $pickingListUuids): PickingListCollectionTransfer
    {
        $pickingListConditions = (new PickingListConditionsTransfer())
            ->setUuids($pickingListUuids);
        $pickingListCriteriaTransfer = (new PickingListCriteriaTransfer())
            ->setPickingListConditions($pickingListConditions);

        return $this->pickingListFacade->getPickingListCollection($pickingListCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function getUserUuidsIndexedByPickingListUuid(PickingListCollectionTransfer $pickingListCollectionTransfer): array
    {
        $userUuidsIndexedByPickingListUuid = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            if ($pickingListTransfer->getUserUuid() !== null) {
                $userUuidsIndexedByPickingListUuid[$pickingListTransfer->getUuidOrFail()] = $pickingListTransfer->getUserUuidOrFail();
            }
        }

        return $userUuidsIndexedByPickingListUuid;
    }

    /**
     * @param array<int, string> $userUuids
     *
     * @return array<string, \Generated\Shared\Transfer\GlueResourceTransfer>
     */
    protected function getUserResourcesIndexedByUuid(array $userUuids): array
    {
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->setUuids($userUuids);
        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions($userConditionsTransfer);

        $userResourceCollectionTransfer = $this->usersBackendApiResource->getUserResourceCollection($userCriteriaTransfer);

        $indexedUserResources = [];
        foreach ($userResourceCollectionTransfer->getUserResources() as $userResource) {
            $indexedUserResources[$userResource->getIdOrFail()] = $userResource;
        }

        return $indexedUserResources;
    }
}
