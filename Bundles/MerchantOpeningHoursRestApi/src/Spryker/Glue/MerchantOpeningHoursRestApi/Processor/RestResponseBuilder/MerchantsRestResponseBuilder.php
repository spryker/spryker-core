<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantMapperInterface;
use Symfony\Component\HttpFoundation\Response;

class MerchantOpeningHoursRestResponseBuilder implements MerchantOpeningHoursRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantMapperInterface
     */
    protected $merchantsResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Mapper\MerchantMapperInterface $merchantsResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        MerchantMapperInterface $merchantsResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->merchantsResourceMapper = $merchantsResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createMerchantOpeningHoursRestResource(MerchantStorageTransfer $merchantStorageTransfer)
    {
        $restMerchantOpeningHoursAttributesTransfer = $this->merchantsResourceMapper->mapMerchantStorageTransferToRestMerchantAttributesTransfer(
            $merchantStorageTransfer,
            new RestMerchantOpeningHoursAttributesTransfer()
        );

        return $this->restResourceBuilder->createRestResource(
            MerchantOpeningHoursRestApiConfig::RESOURCE_MERCHANTS,
            $merchantStorageTransfer->getMerchantReference(),
            $restMerchantOpeningHoursAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantOpeningHoursRestResponse(MerchantStorageTransfer $merchantStorageTransfer): RestResponseInterface
    {
        $merchantsRestResource = $this->createMerchantOpeningHoursRestResource($merchantStorageTransfer);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($merchantsRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantNotFoundError(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setCode(MerchantOpeningHoursRestApiConfig::RESPONSE_CODE_MERCHANT_NOT_FOUND)
            ->setDetail(MerchantOpeningHoursRestApiConfig::RESPONSE_DETAIL_MERCHANT_NOT_FOUND);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }
}
