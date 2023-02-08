<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Expander;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UsersRestAttributesTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer;
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserReaderInterface;
use Spryker\Glue\UsersBackendApi\UsersBackendApiConfig;

class UserByWarehouseUserAssignmentResourceRelationshipExpander implements UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\UsersBackendApi\Processor\Reader\UserReaderInterface
     */
    protected UserReaderInterface $userReader;

    /**
     * @param \Spryker\Glue\UsersBackendApi\Processor\Reader\UserReaderInterface $userReader
     */
    public function __construct(UserReaderInterface $userReader)
    {
        $this->userReader = $userReader;
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $warehouseUserAssignmentsResources
     *
     * @return array<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function expandWarehouseUserAssignmentsResourcesWithUsersResourceRelationships(
        array $warehouseUserAssignmentsResources
    ): array {
        $userUuids = $this->getUserUuids($warehouseUserAssignmentsResources);
        $userCollectionTransfer = $this->userReader->getUserCollectionTransferByUuids($userUuids);
        $indexedUserTransfers = $this->getUserTransfersIndexedByUuid($userCollectionTransfer);

        foreach ($warehouseUserAssignmentsResources as $warehouseUserAssignmentsResource) {
            /** @var \Generated\Shared\Transfer\WarehouseUserAssignmentsRestAttributesTransfer $warehouseUserAssignmentsRestAttributeTransfer */
            $warehouseUserAssignmentsRestAttributeTransfer = $warehouseUserAssignmentsResource->getAttributes();
            if (
                !$warehouseUserAssignmentsRestAttributeTransfer instanceof WarehouseUserAssignmentsRestAttributesTransfer
                || !isset($indexedUserTransfers[$warehouseUserAssignmentsRestAttributeTransfer->getUserUuidOrFail()])
            ) {
                continue;
            }

            $userTransfer = $indexedUserTransfers[$warehouseUserAssignmentsRestAttributeTransfer->getUserUuidOrFail()];
            $usersRestAttributesTransfer = (new UsersRestAttributesTransfer())->fromArray($userTransfer->toArray(), true);
            $usersRestAttributesResourceTransfer = $this->createRestUsersResource($userTransfer, $usersRestAttributesTransfer);
            $warehouseUserAssignmentsResource->addRelationship(
                (new GlueRelationshipTransfer())->addResource($usersRestAttributesResourceTransfer),
            );
        }

        return $warehouseUserAssignmentsResources;
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $warehouseUserAssignmentsResources
     *
     * @return array<string>
     */
    protected function getUserUuids(array $warehouseUserAssignmentsResources): array
    {
        $userUuids = [];
        foreach ($warehouseUserAssignmentsResources as $warehouseUserAssignmentsResource) {
            $warehouseUserAssignmentsRestAttributeTransfer = $warehouseUserAssignmentsResource->getAttributes();
            if (!$warehouseUserAssignmentsRestAttributeTransfer instanceof WarehouseUserAssignmentsRestAttributesTransfer) {
                continue;
            }
            $userUuids[] = $warehouseUserAssignmentsRestAttributeTransfer->getUserUuidOrFail();
        }

        return array_unique($userUuids);
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\UserTransfer>
     */
    protected function getUserTransfersIndexedByUuid(UserCollectionTransfer $userCollectionTransfer): array
    {
        $indexedUserTransfers = [];
        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            $indexedUserTransfers[$userTransfer->getUuidOrFail()] = $userTransfer;
        }

        return $indexedUserTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\UsersRestAttributesTransfer $usersRestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createRestUsersResource(UserTransfer $userTransfer, UsersRestAttributesTransfer $usersRestAttributesTransfer): GlueResourceTransfer
    {
        return (new GlueResourceTransfer())
            ->setType(UsersBackendApiConfig::RESOURCE_TYPE_USERS)
            ->setId($userTransfer->getUuidOrFail())
            ->setAttributes($usersRestAttributesTransfer);
    }
}
