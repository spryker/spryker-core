<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceTypeMapper implements ServiceTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param \Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer
     */
    public function mapServiceTypeTransferToServiceTypesBackendApiAttributesTransfer(
        ServiceTypeTransfer $serviceTypeTransfer,
        ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer
    ): ServiceTypesBackendApiAttributesTransfer {
        return $serviceTypesBackendApiAttributesTransfer->fromArray(
            $serviceTypeTransfer->toArray(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function mapServiceTypesBackendApiAttributesTransferToServiceTypeTransfer(
        ServiceTypesBackendApiAttributesTransfer $serviceTypesBackendApiAttributesTransfer,
        ServiceTypeTransfer $serviceTypeTransfer
    ): ServiceTypeTransfer {
        $serviceTypesBackendApiAttributesData = array_filter(
            $serviceTypesBackendApiAttributesTransfer->modifiedToArray(),
            function ($value) {
                return $value !== null;
            },
        );

        return $serviceTypeTransfer->fromArray($serviceTypesBackendApiAttributesData, true);
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers
     * @param \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer $serviceTypeResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer
     */
    public function mapServiceTypeTransfersToServiceTypeResourceCollectionTransfer(
        ArrayObject $serviceTypeTransfers,
        ServiceTypeResourceCollectionTransfer $serviceTypeResourceCollectionTransfer
    ): ServiceTypeResourceCollectionTransfer {
        foreach ($serviceTypeTransfers as $serviceTypeTransfer) {
            $serviceTypeResourceCollectionTransfer->addServiceTypeResource(
                $this->mapServiceTypeTransferToServiceTypeResourceTransfer(
                    $serviceTypeTransfer,
                    new GlueResourceTransfer(),
                ),
            );
        }

        return $serviceTypeResourceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param \Generated\Shared\Transfer\GlueResourceTransfer $glueResourceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function mapServiceTypeTransferToServiceTypeResourceTransfer(
        ServiceTypeTransfer $serviceTypeTransfer,
        GlueResourceTransfer $glueResourceTransfer
    ): GlueResourceTransfer {
        $serviceTypesBackendApiAttributesTransfer = $this
            ->mapServiceTypeTransferToServiceTypesBackendApiAttributesTransfer(
                $serviceTypeTransfer,
                new ServiceTypesBackendApiAttributesTransfer(),
            );

        return $glueResourceTransfer
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_TYPES)
            ->setId($serviceTypeTransfer->getUuidOrFail())
            ->setAttributes($serviceTypesBackendApiAttributesTransfer);
    }
}
