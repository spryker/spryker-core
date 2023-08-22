<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointResponseBuilder implements ServicePointResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig
     */
    protected ServicePointsBackendApiConfig $servicePointsBackendApiConfig;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface
     */
    protected ServicePointMapperInterface $servicePointMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig $servicePointsBackendApiConfig
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface $servicePointMapper
     */
    public function __construct(
        ServicePointsBackendApiConfig $servicePointsBackendApiConfig,
        ServicePointMapperInterface $servicePointMapper
    ) {
        $this->servicePointsBackendApiConfig = $servicePointsBackendApiConfig;
        $this->servicePointMapper = $servicePointMapper;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServicePointResponse(
        ArrayObject $servicePointTransfers
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($servicePointTransfers as $servicePointTransfer) {
            $glueResponseTransfer->addResource(
                $this->createServicePointResourceTransfer($servicePointTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createServicePointResourceTransfer(
        ServicePointTransfer $servicePointTransfer
    ): GlueResourceTransfer {
        $servicePointsBackendApiAttributesTransfer = $this->servicePointMapper
            ->mapServicePointTransferToServicePointsBackendApiAttributesTransfer(
                $servicePointTransfer,
                new ServicePointsBackendApiAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($servicePointTransfer->getUuidOrFail())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS)
            ->setAttributes($servicePointsBackendApiAttributesTransfer);
    }
}
