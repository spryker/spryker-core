<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServiceTypeResourceCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceTypeMapper implements ServiceTypeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     * @param \Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer
     */
    public function mapServiceTypeTransferToApiServiceTypesAttributesTransfer(
        ServiceTypeTransfer $serviceTypeTransfer,
        ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer
    ): ApiServiceTypesAttributesTransfer {
        return $apiServiceTypesAttributesTransfer->fromArray(
            $serviceTypeTransfer->toArray(),
            true,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    public function mapApiServiceTypesAttributesTransferToServiceTypeTransfer(
        ApiServiceTypesAttributesTransfer $apiServiceTypesAttributesTransfer,
        ServiceTypeTransfer $serviceTypeTransfer
    ): ServiceTypeTransfer {
        $apiServiceTypesAttributesData = array_filter(
            $apiServiceTypesAttributesTransfer->modifiedToArray(),
            function ($value) {
                return $value !== null;
            },
        );

        return $serviceTypeTransfer->fromArray($apiServiceTypesAttributesData, true);
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
        $apiServiceTypesAttributesTransfer = $this
            ->mapServiceTypeTransferToApiServiceTypesAttributesTransfer(
                $serviceTypeTransfer,
                new ApiServiceTypesAttributesTransfer(),
            );

        return $glueResourceTransfer
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_TYPES)
            ->setId($serviceTypeTransfer->getUuidOrFail())
            ->setAttributes($apiServiceTypesAttributesTransfer);
    }
}
