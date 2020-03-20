<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantOpeningHourMapperInterface;

class MerchantOpeningHourRestResponseBuilder implements MerchantOpeningHourRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantOpeningHourMapperInterface
     */
    protected $merchantsResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantOpeningHourMapperInterface $merchantsResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        MerchantOpeningHourMapperInterface $merchantsResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->merchantsResourceMapper = $merchantsResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createMerchantOpeningHoursRestResource(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        string $merchantReference
    ): RestResourceInterface {
        $restMerchantOpeningHoursAttributesTransfer = $this->merchantsResourceMapper->mapMerchantStorageTransferToRestMerchantOpeningHoursAttributesTransfer(
            $merchantOpeningHoursStorageTransfer,
            new RestMerchantOpeningHoursAttributesTransfer()
        );

        return $this->restResourceBuilder->createRestResource(
            MerchantOpeningHoursRestApiConfig::RESOURCE_MERCHANT_OPENING_HOURS,
            $merchantReference,
            $restMerchantOpeningHoursAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantOpeningHoursRestResponse(
        MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer,
        string $merchantReference
    ): RestResponseInterface {
        $merchantsRestResource = $this->createMerchantOpeningHoursRestResource($merchantOpeningHoursStorageTransfer, $merchantReference);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($merchantsRestResource);
    }
}
