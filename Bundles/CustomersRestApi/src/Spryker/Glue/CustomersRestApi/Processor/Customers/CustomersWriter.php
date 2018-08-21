<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomersWriter implements CustomersWriterInterface
{
    const ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    protected $customersResourceMapper;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface $customersResourceMapper
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersResourceMapperInterface $customersResourceMapper
    ) {
        $this->customerClient = $customerClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customersResourceMapper = $customersResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $restCustomersAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function registerCustomer(RestCustomersAttributesTransfer $restCustomersAttributesTransfer): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = $this->customersResourceMapper->mapCustomerAttributesToCustomerTransfer($restCustomersAttributesTransfer);
        $customerResponseTransfer = $this->customerClient->registerCustomer($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $this->createErrorResponse($customerResponseTransfer, $response);
        }

        $restResource = $this->customersResourceMapper->mapCustomerToCustomersRestResource($customerResponseTransfer->getCustomerTransfer());

        return $response->addResource($restResource);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $restCustomersAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteCustomer(RestCustomersAttributesTransfer $restCustomersAttributesTransfer): RestResponseInterface
    {
        $customerTransfer = (new CustomerTransfer())->fromArray($restCustomersAttributesTransfer->toArray(), true);
        $customerTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        $response = $this->restResourceBuilder->createRestResponse();

        if (!$customerTransfer) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

            $response->addError($restErrorTransfer);

            return $response;
        }

        $this->customerClient->anonymizeCustomer($customerTransfer);

        return $response;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorCustomerAlreadyExists(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_ALREADY_EXISTS)
            ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->setDetail(CustomersRestApiConfig::RESPONSE_MESSAGE_CUSTOMER_ALREADY_EXISTS);
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createErrorCustomerCantRegisterCustomerMessage(string $errorMessage): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_CANT_REGISTER_CUSTOMER)
            ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setDetail($errorMessage);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorResponse(CustomerResponseTransfer $customerResponseTransfer, RestResponseInterface $response): RestResponseInterface
    {
        foreach ($customerResponseTransfer->getErrors() as $error) {
            if ($error->getMessage() === static::ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED) {
                return $response->addError($this->createErrorCustomerAlreadyExists());
            }
            $response->addError($this->createErrorCustomerCantRegisterCustomerMessage($error->getMessage()));
        }

        return $response;
    }
}
