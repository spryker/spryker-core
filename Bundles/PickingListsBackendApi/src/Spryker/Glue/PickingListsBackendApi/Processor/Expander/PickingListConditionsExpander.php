<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\PickingListsBackendApi\PickingListsBackendApiConfig;
use Spryker\Glue\PickingListsBackendApi\Processor\Extractor\WarehouseUserAssignmentExtractorInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\StockReaderInterface;
use Spryker\Glue\PickingListsBackendApi\Processor\Reader\WarehouseUserAssignmentReader;

class PickingListConditionsExpander implements PickingListConditionsExpanderInterface
{
    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Reader\StockReaderInterface
     */
    protected StockReaderInterface $stockReader;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Reader\WarehouseUserAssignmentReader
     */
    protected WarehouseUserAssignmentReader $warehouseUserAssignmentReader;

    /**
     * @var \Spryker\Glue\PickingListsBackendApi\Processor\Extractor\WarehouseUserAssignmentExtractorInterface
     */
    protected WarehouseUserAssignmentExtractorInterface $warehouseUserAssignmentExtractor;

    /**
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Reader\StockReaderInterface $stockReader
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Reader\WarehouseUserAssignmentReader $warehouseUserAssignmentReader
     * @param \Spryker\Glue\PickingListsBackendApi\Processor\Extractor\WarehouseUserAssignmentExtractorInterface $warehouseUserAssignmentExtractor
     */
    public function __construct(
        StockReaderInterface $stockReader,
        WarehouseUserAssignmentReader $warehouseUserAssignmentReader,
        WarehouseUserAssignmentExtractorInterface $warehouseUserAssignmentExtractor
    ) {
        $this->stockReader = $stockReader;
        $this->warehouseUserAssignmentReader = $warehouseUserAssignmentReader;
        $this->warehouseUserAssignmentExtractor = $warehouseUserAssignmentExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListConditionsTransfer $pickingListConditionsTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListConditionsTransfer
     */
    public function expandWithPickingListRequestData(
        PickingListConditionsTransfer $pickingListConditionsTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): PickingListConditionsTransfer {
        if ($glueRequestTransfer->getRequestUser() !== null) {
            return $this->expandWithUserData($pickingListConditionsTransfer, $glueRequestTransfer->getRequestUserOrFail());
        }

        $stockTransfer = $this->stockReader->getStockTransfer($glueRequestTransfer);
        if ($stockTransfer === null || $stockTransfer->getUuid() === null) {
            return $pickingListConditionsTransfer;
        }

        $pickingListConditionsTransfer->addWarehouseUuid($stockTransfer->getUuidOrFail());

        return $pickingListConditionsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListConditionsTransfer $pickingListConditionsTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListConditionsTransfer
     */
    public function expandWithPickingListCollectionRequestData(
        PickingListConditionsTransfer $pickingListConditionsTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): PickingListConditionsTransfer {
        $pickingListConditionsTransfer = $this->expandWithFilters($pickingListConditionsTransfer, $glueRequestTransfer);

        return $this->expandWithPickingListRequestData($pickingListConditionsTransfer, $glueRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListConditionsTransfer $pickingListConditionsTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListConditionsTransfer
     */
    protected function expandWithFilters(
        PickingListConditionsTransfer $pickingListConditionsTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): PickingListConditionsTransfer {
        foreach ($glueRequestTransfer->getFilters() as $glueFilterTransfer) {
            if ($glueFilterTransfer->getResourceOrFail() !== PickingListsBackendApiConfig::RESOURCE_PICKING_LISTS) {
                return $pickingListConditionsTransfer;
            }

            if ($glueFilterTransfer->getField() === PickingListConditionsTransfer::UUIDS) {
                /** @var list<string>|string $filterValue */
                $filterValue = $glueFilterTransfer->getValueOrFail();

                if (!is_array($filterValue)) {
                    continue;
                }

                $pickingListConditionsTransfer->setUuids(array_filter($filterValue, 'is_string'));
            }
        }

        return $pickingListConditionsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListConditionsTransfer $pickingListConditionsTransfer
     * @param \Generated\Shared\Transfer\GlueRequestUserTransfer $glueRequestUserTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListConditionsTransfer
     */
    protected function expandWithUserData(
        PickingListConditionsTransfer $pickingListConditionsTransfer,
        GlueRequestUserTransfer $glueRequestUserTransfer
    ): PickingListConditionsTransfer {
        $userUuid = $glueRequestUserTransfer->getNaturalIdentifierOrFail();

        $pickingListConditionsTransfer->addUserUuid($userUuid);
        $pickingListConditionsTransfer->setWithUnassignedUser(false);

        $warehouseUserAssignmentCollectionTransfer = $this
            ->warehouseUserAssignmentReader
            ->getWarehouseUserAssignmentCollection((new UserTransfer())->setUuid($userUuid));
        $warehouseUuids = $this->warehouseUserAssignmentExtractor->extractWarehouseUuids($warehouseUserAssignmentCollectionTransfer);
        if ($warehouseUuids) {
            $pickingListConditionsTransfer->setWarehouseUuids($warehouseUuids)
                ->setWithUnassignedUser(true);
        }

        return $pickingListConditionsTransfer;
    }
}
