<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ApiServicesAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceResponseBuilder implements ServiceResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface
     */
    protected ServiceMapperInterface $serviceMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceMapperInterface $serviceMapper
     */
    public function __construct(ServiceMapperInterface $serviceMapper)
    {
        $this->serviceMapper = $serviceMapper;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServiceResponse(
        ArrayObject $serviceTransfers
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($serviceTransfers as $serviceTransfer) {
            $glueResponseTransfer->addResource(
                $this->createServiceResourceTransfer($serviceTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createServiceResourceTransfer(
        ServiceTransfer $serviceTransfer
    ): GlueResourceTransfer {
        $apiServicesAttributesTransfer = $this->serviceMapper
            ->mapServiceTransferToApiServicesAttributesTransfer(
                $serviceTransfer,
                new ApiServicesAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($serviceTransfer->getUuidOrFail())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICES)
            ->setAttributes($apiServicesAttributesTransfer);
    }
}
