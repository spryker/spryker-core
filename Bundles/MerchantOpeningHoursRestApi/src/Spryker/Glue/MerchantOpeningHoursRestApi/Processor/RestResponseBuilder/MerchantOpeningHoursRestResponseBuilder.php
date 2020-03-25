<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestMerchantOpeningHoursAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class MerchantOpeningHoursRestResponseBuilder implements MerchantOpeningHoursRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
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
        return $this->restResourceBuilder->createRestResource(
            MerchantOpeningHoursRestApiConfig::RESOURCE_MERCHANT_OPENING_HOURS,
            $merchantReference,
            (new RestMerchantOpeningHoursAttributesTransfer())->fromArray($merchantOpeningHoursStorageTransfer->toArray(), true)
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

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantNotFoundError(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setCode(MerchantOpeningHoursRestApiConfig::RESPONSE_CODE_MERCHANT_NOT_FOUND)
                    ->setDetail(MerchantOpeningHoursRestApiConfig::RESPONSE_DETAIL_MERCHANT_NOT_FOUND)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantIdentifierMissingErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setCode(MerchantOpeningHoursRestApiConfig::RESPONSE_CODE_MERCHANT_IDENTIFIER_MISSING)
                    ->setDetail(MerchantOpeningHoursRestApiConfig::RESPONSE_DETAIL_MERCHANT_IDENTIFIER_MISSING)
            );
    }
}
