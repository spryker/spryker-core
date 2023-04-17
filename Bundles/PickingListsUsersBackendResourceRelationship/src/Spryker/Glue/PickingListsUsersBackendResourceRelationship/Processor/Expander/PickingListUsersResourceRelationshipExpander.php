<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Filter\PickingListResourceFilterInterface;
use Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Reader\PickingListUsersResourceRelationshipReaderInterface;

class PickingListUsersResourceRelationshipExpander implements PickingListUsersResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Filter\PickingListResourceFilterInterface
     */
    protected PickingListResourceFilterInterface $pickingListResourceFilter;

    /**
     * @var \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Reader\PickingListUsersResourceRelationshipReaderInterface
     */
    protected PickingListUsersResourceRelationshipReaderInterface $pickingListUsersResourceRelationshipReader;

    /**
     * @param \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Filter\PickingListResourceFilterInterface $pickingListResourceFilter
     * @param \Spryker\Glue\PickingListsUsersBackendResourceRelationship\Processor\Reader\PickingListUsersResourceRelationshipReaderInterface $pickingListUsersResourceRelationshipReader
     */
    public function __construct(
        PickingListResourceFilterInterface $pickingListResourceFilter,
        PickingListUsersResourceRelationshipReaderInterface $pickingListUsersResourceRelationshipReader
    ) {
        $this->pickingListResourceFilter = $pickingListResourceFilter;
        $this->pickingListUsersResourceRelationshipReader = $pickingListUsersResourceRelationshipReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addPickingListUsersRelationships(array $glueResourceTransfers, GlueRequestTransfer $glueRequestTransfer): void
    {
        $pickingListResourceTransfers = $this->pickingListResourceFilter->filterPickingListResources($glueResourceTransfers);
        $pickingListUuids = $this->extractPickingListUuids($pickingListResourceTransfers);

        $userRelationshipTransfersIndexedByPickingListUuid = $this->pickingListUsersResourceRelationshipReader
            ->getUserRelationshipsIndexedByPickingListUuid($pickingListUuids);

        $this->addUserRelationshipsToGlueResourceTransfers(
            $glueResourceTransfers,
            $userRelationshipTransfersIndexedByPickingListUuid,
        );
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    protected function extractPickingListUuids(array $glueResourceTransfers): array
    {
        $pickingListUuids = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            $pickingListUuids[] = $glueResourceTransfer->getIdOrFail();
        }

        return $pickingListUuids;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param array<string, \Generated\Shared\Transfer\GlueRelationshipTransfer> $userRelationshipTransfersIndexedByPickingListUuid
     *
     * @return void
     */
    protected function addUserRelationshipsToGlueResourceTransfers(
        array $glueResourceTransfers,
        array $userRelationshipTransfersIndexedByPickingListUuid
    ): void {
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            $userRelationshipTransfer = $userRelationshipTransfersIndexedByPickingListUuid[$glueResourceTransfer->getIdOrFail()] ?? null;

            if ($userRelationshipTransfer) {
                $glueResourceTransfer->addRelationship($userRelationshipTransfer);
            }
        }
    }
}
