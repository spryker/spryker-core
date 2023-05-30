<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ApiServiceTypesAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServiceTypeResponseBuilder implements ServiceTypeResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig
     */
    protected ServicePointsBackendApiConfig $servicePointsBackendApiConfig;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface
     */
    protected ServiceTypeMapperInterface $serviceTypeMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig $servicePointsBackendApiConfig
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServiceTypeMapperInterface $serviceTypeMapper
     */
    public function __construct(
        ServicePointsBackendApiConfig $servicePointsBackendApiConfig,
        ServiceTypeMapperInterface $serviceTypeMapper
    ) {
        $this->servicePointsBackendApiConfig = $servicePointsBackendApiConfig;
        $this->serviceTypeMapper = $serviceTypeMapper;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServiceTypeResponse(
        ArrayObject $serviceTypeTransfers
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($serviceTypeTransfers as $serviceTypeTransfer) {
            $glueResponseTransfer->addResource(
                $this->createServiceTypeResourceTransfer($serviceTypeTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createServiceTypeResourceTransfer(
        ServiceTypeTransfer $serviceTypeTransfer
    ): GlueResourceTransfer {
        $apiServiceTypesAttributesTransfer = $this->serviceTypeMapper
            ->mapServiceTypeTransferToApiServiceTypesAttributesTransfer(
                $serviceTypeTransfer,
                new ApiServiceTypesAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($serviceTypeTransfer->getUuidOrFail())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_TYPES)
            ->setAttributes($apiServiceTypesAttributesTransfer);
    }
}
