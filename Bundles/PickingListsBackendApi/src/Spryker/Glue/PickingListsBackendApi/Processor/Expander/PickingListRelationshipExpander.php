<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Expander;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapperInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface;

class PickingListRelationshipExpander implements PickingListRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface
     */
    protected PickingListReaderInterface $pickingListReader;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapperInterface
     */
    protected PickingListItemMapperInterface $pickingListItemsMapper;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Reader\PickingListReaderInterface $pickingListReader
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Mapper\PickingListItemMapperInterface $pickingListItemsMapper
     */
    public function __construct(
        PickingListReaderInterface $pickingListReader,
        PickingListItemMapperInterface $pickingListItemsMapper
    ) {
        $this->pickingListReader = $pickingListReader;
        $this->pickingListItemsMapper = $pickingListItemsMapper;
    }

    /**
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addPickingListItemsResourceRelationships(
        array $glueResourceTransfers,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $pickingListCollectionTransfer = $this->pickingListReader->getPickingListCollectionByPickingListUuids(
            $this->extractPickingListUuidsFromGlueResourceTransfers($glueResourceTransfers),
        );
        $pickingListItemTransfersGroupedByPickingListUuid = $this->getPickingListItemsGroupedByPickingListUuid($pickingListCollectionTransfer);

        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if (!$this->isApplicablePickingListResource($glueResourceTransfer)) {
                continue;
            }

            $pickingListUuid = $glueResourceTransfer->getIdOrFail();
            $this->addPickingListItemsResourceRelationshipToGlueResourceTransfer(
                $glueResourceTransfer,
                $pickingListItemTransfersGroupedByPickingListUuid[$pickingListUuid],
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\PickingListItemTransfer>>
     */
    protected function getPickingListItemsGroupedByPickingListUuid(PickingListCollectionTransfer $pickingListCollectionTransfer): array
    {
        $groupedPickingListItemTransfers = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $groupedPickingListItemTransfers[$pickingListTransfer->getUuidOrFail()] = (array)$pickingListTransfer->getPickingListItems();
        }

        return $groupedPickingListItemTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    protected function extractPickingListUuidsFromGlueResourceTransfers(array $glueResourceTransfers): array
    {
        $pickingListUuids = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if (!$this->isApplicablePickingListResource($glueResourceTransfer)) {
                continue;
            }

            $pickingListUuids[] = $glueResourceTransfer->getIdOrFail();
        }

        return $pickingListUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return bool
     */
    protected function isApplicablePickingListResource(
        GlueResourceTransfer $glueResourceTransfer
    ): bool {
        return $glueResourceTransfer->getType() === PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     * @param list<\Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     *
     * @return void
     */
    protected function addPickingListItemsResourceRelationshipToGlueResourceTransfer(
        GlueResourceTransfer $glueResourceTransfer,
        array $pickingListItemTransfers
    ): void {
        $glueRelationshipTransfer = $this->pickingListItemsMapper
            ->mapPickingListItemTransfersToGlueRelationshipTransfer(
                $pickingListItemTransfers,
                new GlueRelationshipTransfer(),
            );

        $glueResourceTransfer->addRelationship($glueRelationshipTransfer);
    }
}
