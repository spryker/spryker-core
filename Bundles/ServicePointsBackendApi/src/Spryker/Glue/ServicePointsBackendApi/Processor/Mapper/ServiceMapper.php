<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRelationshipTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServicesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicesRequestBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceMapper implements ServiceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\ServicesBackendApiAttributesTransfer $servicesBackendApiAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ServicesBackendApiAttributesTransfer
     */
    public function mapServiceTransferToServicesBackendApiAttributesTransfer(
        ServiceTransfer $serviceTransfer,
        ServicesBackendApiAttributesTransfer $servicesBackendApiAttributesTransfer
    ): ServicesBackendApiAttributesTransfer {
        return $servicesBackendApiAttributesTransfer->fromArray($serviceTransfer->modifiedToArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    public function mapServicesRequestBackendApiAttributesTransferToServiceTransfer(
        ServicesRequestBackendApiAttributesTransfer $servicesRequestBackendApiAttributesTransfer,
        ServiceTransfer $serviceTransfer
    ): ServiceTransfer {
        $serviceTransfer->fromArray($servicesRequestBackendApiAttributesTransfer->modifiedToArray(), true);
        $serviceTypeTransfer = (new ServiceTypeTransfer())->setUuid($servicesRequestBackendApiAttributesTransfer->getServiceTypeUuid());
        $servicePointTransfer = (new ServicePointTransfer())->setUuid($servicesRequestBackendApiAttributesTransfer->getServicePointUuid());

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
        $servicesBackendApiAttributesTransfer = $this->mapServiceTransferToServicesBackendApiAttributesTransfer(
            $serviceTransfer,
            new ServicesBackendApiAttributesTransfer(),
        );

        $glueResourceTransfer = (new GlueResourceTransfer())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICES)
            ->setId($servicesBackendApiAttributesTransfer->getUuidOrFail())
            ->setAttributes($servicesBackendApiAttributesTransfer);

        return $glueRelationshipTransfer->addResource($glueResourceTransfer);
    }
}
