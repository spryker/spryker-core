<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ApiServicesAttributesTransfer;
use Generated\Shared\Transfer\ApiServicesRequestAttributesTransfer;
use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceMapper implements ServiceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\ApiServicesAttributesTransfer $apiServicesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ApiServicesAttributesTransfer
     */
    public function mapServiceTransferToApiServicesAttributesTransfer(
        ServiceTransfer $serviceTransfer,
        ApiServicesAttributesTransfer $apiServicesAttributesTransfer
    ): ApiServicesAttributesTransfer {
        return $apiServicesAttributesTransfer->fromArray($serviceTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiServicesRequestAttributesTransfer $apiServicesRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function mapApiServicesRequestAttributesTransferToServiceTransfer(
        ApiServicesRequestAttributesTransfer $apiServicesRequestAttributesTransfer,
        ServiceTransfer $serviceTransfer
    ): ServiceTransfer {
        $serviceTransfer->fromArray($apiServicesRequestAttributesTransfer->modifiedToArray(), true);
        $serviceTypeTransfer = (new ServiceTypeTransfer())->setUuid($apiServicesRequestAttributesTransfer->getServiceTypeUuid());
        $servicePointTransfer = (new ServicePointTransfer())->setUuid($apiServicesRequestAttributesTransfer->getServicePointUuid());

        return $serviceTransfer->setServiceType($serviceTypeTransfer)->setServicePoint($servicePointTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\GlueRelationshipTransfer $glueRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRelationshipTransfer
     */
    public function mapServiceTransferToGlueRelationshipTransfer(
        ServiceTransfer $serviceTransfer,
        GlueRelationshipTransfer $glueRelationshipTransfer
    ): GlueRelationshipTransfer {
        $apiServicesAttributesTransfer = $this->mapServiceTransferToApiServicesAttributesTransfer(
            $serviceTransfer,
            new ApiServicesAttributesTransfer(),
        );

        $glueResourceTransfer = (new GlueResourceTransfer())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICES)
            ->setId($apiServicesAttributesTransfer->getUuidOrFail())
            ->setAttributes($apiServicesAttributesTransfer);

        return $glueRelationshipTransfer->addResource($glueResourceTransfer);
    }
}
