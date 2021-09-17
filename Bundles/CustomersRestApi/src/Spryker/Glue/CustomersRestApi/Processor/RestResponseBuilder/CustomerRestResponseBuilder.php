<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomerRestResponseBuilder implements CustomerRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig
     */
    protected $customersRestApiConfig;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\CustomersRestApiConfig $customersRestApiConfig
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiConfig $customersRestApiConfig
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customersRestApiConfig = $customersRestApiConfig;
    }

    /**
     * @param string $customerUuid
     * @param \Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer $restCustomersResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCustomerRestResource(
        string $customerUuid,
        RestCustomersResponseAttributesTransfer $restCustomersResponseAttributesTransfer,
        ?CustomerTransfer $customerTransfer = null
    ): RestResourceInterface {
        $customerRestResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            $customerUuid,
            $restCustomersResponseAttributesTransfer
        );

        if ($customerTransfer) {
            $customerRestResource->setPayload($customerTransfer);
        }

        return $customerRestResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createNoContentResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->setStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CustomerErrorTransfer> $customerErrorTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerConfirmationErrorResponse(ArrayObject $customerErrorTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse = $this->mapCustomerErrorsToRestResponse($customerErrorTransfers, $restResponse);

        if (!count($restResponse->getErrors())) {
            $this->addCustomerConfirmationFailedError($restResponse);
        }

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerConfirmationCodeMissingErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CONFIRMATION_CODE_MISSING)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(CustomersRestApiConfig::RESPONSE_MESSAGE_CONFIRMATION_CODE_MISSING);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addCustomerConfirmationFailedError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CONFIRMATION_FAILED)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(CustomersRestApiConfig::RESPONSE_MESSAGE_CONFIRMATION_FAILED);

        $restResponse->addError($restErrorMessageTransfer);

        return $restResponse;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CustomerErrorTransfer> $customerErrorTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function mapCustomerErrorsToRestResponse(ArrayObject $customerErrorTransfers, RestResponseInterface $restResponse): RestResponseInterface
    {
        $errorMapping = $this->customersRestApiConfig->getErrorMapping();

        foreach ($customerErrorTransfers as $customerErrorTransfer) {
            if (!isset($errorMapping[$customerErrorTransfer->getMessage()])) {
                continue;
            }

            $restErrorMessageTransfer = (new RestErrorMessageTransfer())
                ->fromArray($errorMapping[$customerErrorTransfer->getMessage()], true);

            $restResponse->addError($restErrorMessageTransfer);
        }

        return $restResponse;
    }
}
