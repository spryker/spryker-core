<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ApiServicePointAddressesAttributesTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;

class ServicePointAddressResponseBuilder implements ServicePointAddressResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface
     */
    protected ServicePointAddressMapperInterface $servicePointAddressMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointAddressMapperInterface $servicePointAddressMapper
     */
    public function __construct(ServicePointAddressMapperInterface $servicePointAddressMapper)
    {
        $this->servicePointAddressMapper = $servicePointAddressMapper;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointAddressTransfer> $servicePointAddressTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServicePointAddressResponse(
        ArrayObject $servicePointAddressTransfers
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($servicePointAddressTransfers as $servicePointAddressTransfer) {
            $glueResponseTransfer->addResource(
                $this->createServicePointAddressResourceTransfer($servicePointAddressTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressTransfer $servicePointAddressTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createServicePointAddressResourceTransfer(
        ServicePointAddressTransfer $servicePointAddressTransfer
    ): GlueResourceTransfer {
        $apiServicePointAddressesAttributesTransfer = $this->servicePointAddressMapper
            ->mapServicePointAddressTransferToApiServicePointAddressesAttributesTransfer(
                $servicePointAddressTransfer,
                new ApiServicePointAddressesAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($servicePointAddressTransfer->getUuidOrFail())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINT_ADDRESSES)
            ->setAttributes($apiServicePointAddressesAttributesTransfer);
    }
}
