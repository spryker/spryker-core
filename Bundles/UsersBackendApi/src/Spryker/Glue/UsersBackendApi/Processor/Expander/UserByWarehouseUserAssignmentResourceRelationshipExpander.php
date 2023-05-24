<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi\Processor\Expander;

use Spryker\Glue\UsersBackendApi\Processor\Filter\WarehouseUserAssignmentResourceFilterInterface;
use Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReaderInterface;

class UserByWarehouseUserAssignmentResourceRelationshipExpander implements UserByWarehouseUserAssignmentResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\UsersBackendApi\Processor\Filter\WarehouseUserAssignmentResourceFilterInterface
     */
    protected WarehouseUserAssignmentResourceFilterInterface $warehouseUserAssignmentResourceFilter;

    /**
     * @var \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReaderInterface
     */
    protected UserResourceRelationshipReaderInterface $userResourceRelationshipReader;

    /**
     * @param \Spryker\Glue\UsersBackendApi\Processor\Filter\WarehouseUserAssignmentResourceFilterInterface $warehouseUserAssignmentResourceFilter
     * @param \Spryker\Glue\UsersBackendApi\Processor\Reader\UserResourceRelationshipReaderInterface $userResourceRelationshipReader
     */
    public function __construct(
        WarehouseUserAssignmentResourceFilterInterface $warehouseUserAssignmentResourceFilter,
        UserResourceRelationshipReaderInterface $userResourceRelationshipReader
    ) {
        $this->warehouseUserAssignmentResourceFilter = $warehouseUserAssignmentResourceFilter;
        $this->userResourceRelationshipReader = $userResourceRelationshipReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return void
     */
    public function addUserRelationships(array $glueResourceTransfers): void
    {
        $warehouseUserAssignmentsResources = $this->warehouseUserAssignmentResourceFilter->filterWarehouseUserAssignmentResources($glueResourceTransfers);
        $userUuids = $this->extractUserUuids($warehouseUserAssignmentsResources);

        $userGlueRelationshipTransfersIndexedByUserUuid = $this->userResourceRelationshipReader->getUserRelationshipsIndexedByUserUuid($userUuids);

        $this->addUserRelationshipsToGlueResourceTransfers(
            $warehouseUserAssignmentsResources,
            $userGlueRelationshipTransfersIndexedByUserUuid,
        );
    }

    /**
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\Processor\Expander\UserByWarehouseUserAssignmentResourceRelationshipExpander::addUserRelationships()} instead.
     *
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return void
     */
    public function addUserRelationshipsWithUsersRestAttributes(array $glueResourceTransfers): void
    {
        $warehouseUserAssignmentsResources = $this->warehouseUserAssignmentResourceFilter->filterWarehouseUserAssignmentResources($glueResourceTransfers);
        $userUuids = $this->extractUserUuids($warehouseUserAssignmentsResources);

        $userGlueRelationshipTransfersIndexedByUserUuid = $this->userResourceRelationshipReader->getUserRelationshipsWithUsersRestAttributesIndexedByUserUuid($userUuids);

        $this->addUserRelationshipsToGlueResourceTransfers(
            $warehouseUserAssignmentsResources,
            $userGlueRelationshipTransfersIndexedByUserUuid,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $warehouseUserAssignmentsResources
     *
     * @return list<string>
     */
    protected function extractUserUuids(array $warehouseUserAssignmentsResources): array
    {
        $userUuids = [];
        foreach ($warehouseUserAssignmentsResources as $warehouseUserAssignmentsResource) {
            /** @var \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer */
            $apiWarehouseUserAssignmentsAttributesTransfer = $warehouseUserAssignmentsResource->getAttributesOrFail();
            if (!in_array($apiWarehouseUserAssignmentsAttributesTransfer->getUserUuidOrFail(), $userUuids)) {
                $userUuids[] = $apiWarehouseUserAssignmentsAttributesTransfer->getUserUuidOrFail();
            }
        }

        return $userUuids;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $warehouseUserAssignmentsResources
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $userGlueRelationshipTransfersIndexedByUserUuid
     *
     * @return void
     */
    protected function addUserRelationshipsToGlueResourceTransfers(
        array $warehouseUserAssignmentsResources,
        array $userGlueRelationshipTransfersIndexedByUserUuid
    ): void {
        foreach ($warehouseUserAssignmentsResources as $warehouseUserAssignmentsResource) {
            /** @var \Generated\Shared\Transfer\ApiWarehouseUserAssignmentsAttributesTransfer $apiWarehouseUserAssignmentsAttributesTransfer */
            $apiWarehouseUserAssignmentsAttributesTransfer = $warehouseUserAssignmentsResource->getAttributesOrFail();
            $userGlueRelationshipTransfer = $userGlueRelationshipTransfersIndexedByUserUuid[$apiWarehouseUserAssignmentsAttributesTransfer->getUserUuidOrFail()] ?? null;

            if (!$userGlueRelationshipTransfer) {
                continue;
            }

            $warehouseUserAssignmentsResource->addRelationship($userGlueRelationshipTransfer);
        }
    }
}
