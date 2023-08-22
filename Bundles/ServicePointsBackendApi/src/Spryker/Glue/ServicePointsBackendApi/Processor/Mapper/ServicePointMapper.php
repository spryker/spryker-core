<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServicePointResourceCollectionTransfer;
use Generated\Shared\Transfer\ServicePointsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointMapper implements ServicePointMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\ServicePointsBackendApiAttributesTransfer $servicePointsBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointsBackendApiAttributesTransfer
     */
    public function mapServicePointTransferToServicePointsBackendApiAttributesTransfer(
        ServicePointTransfer $servicePointTransfer,
        ServicePointsBackendApiAttributesTransfer $servicePointsBackendApiAttributesTransfer
    ): ServicePointsBackendApiAttributesTransfer {
        $servicePointsBackendApiAttributesTransfer->fromArray(
            $servicePointTransfer->toArray(),
            true,
        );

        foreach ($servicePointTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
            $servicePointsBackendApiAttributesTransfer->addStore($storeTransfer->getNameOrFail());
        }

        return $servicePointsBackendApiAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointsBackendApiAttributesTransfer $servicePointsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function mapServicePointsBackendApiAttributesTransferToServicePointTransfer(
        ServicePointsBackendApiAttributesTransfer $servicePointsBackendApiAttributesTransfer,
        ServicePointTransfer $servicePointTransfer
    ): ServicePointTransfer {
        $servicePointsBackendApiAttributesData = array_filter(
            $servicePointsBackendApiAttributesTransfer->modifiedToArray(),
            function ($value) {
                return $value !== null;
            },
        );

        $servicePointTransfer->fromArray($servicePointsBackendApiAttributesData, true);

        if ($servicePointsBackendApiAttributesTransfer->getStores()) {
            $servicePointTransfer->setStoreRelation(
                $this->mapStoreNamesToStoreRelationTransfer(
                    $servicePointsBackendApiAttributesTransfer->getStores(),
                    new StoreRelationTransfer(),
                ),
            );
        }

        return $servicePointTransfer;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     * @param \Generated\Shared\Transfer\ServicePointResourceCollectionTransfer $servicePointResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointResourceCollectionTransfer
     */
    public function mapServicePointTransfersToServicePointResourceCollectionTransfer(
        ArrayObject $servicePointTransfers,
        ServicePointResourceCollectionTransfer $servicePointResourceCollectionTransfer
    ): ServicePointResourceCollectionTransfer {
        foreach ($servicePointTransfers as $servicePointTransfer) {
            $servicePointResourceCollectionTransfer->addServicePointResource(
                $this->mapServicePointTransferToServicePointResourceTransfer(
                    $servicePointTransfer,
                    new GlueResourceTransfer(),
                ),
            );
        }

        return $servicePointResourceCollectionTransfer;
    }

    /**
     * @param list<string> $storeNames
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function mapStoreNamesToStoreRelationTransfer(
        array $storeNames,
        StoreRelationTransfer $storeRelationTransfer
    ): StoreRelationTransfer {
        foreach ($storeNames as $storeName) {
            $storeRelationTransfer->addStores(
                (new StoreTransfer())->setName($storeName),
            );
        }

        return $storeRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapServicePointTransferToServicePointResourceTransfer(
        ServicePointTransfer $servicePointTransfer,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        $servicePointsBackendApiAttributesTransfer = $this
            ->mapServicePointTransferToServicePointsBackendApiAttributesTransfer(
                $servicePointTransfer,
                new ServicePointsBackendApiAttributesTransfer(),
            );

        return $glueResourceTransfer
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS)
            ->setId($servicePointTransfer->getUuidOrFail())
            ->setAttributes($servicePointsBackendApiAttributesTransfer);
    }
}
