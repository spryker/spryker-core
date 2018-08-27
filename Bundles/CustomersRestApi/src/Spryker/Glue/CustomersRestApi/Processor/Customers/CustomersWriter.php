<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomersWriter implements CustomersWriterInterface
{
    protected const ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';
    protected const ERROR_CUSTOMER_PASSWORD_INVALID = 'customer.password.invalid';

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface
     */
    protected $customersReader;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface
     */
    protected $customersResourceMapper;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Processor\Customers\CustomersReaderInterface $customersReader
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface $customersResourceMapper
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersReaderInterface $customersReader,
        CustomersResourceMapperInterface $customersResourceMapper
    ) {
        $this->customerClient = $customerClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customersReader = $customersReader;
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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateCustomerPassword(RestRequestInterface $restRequest, RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $user = $restRequest->getUser();
        if (!$user) {
            return $this->createCustomerReferenceMissingError($restResponse);
        }

        $customerTransfer = $this->customersReader->findCustomerByReference($user->getNaturalIdentifier());
        if (!$customerTransfer) {
            return $this->createCustomerNotFoundError($restResponse);
        }

        if (!$this->assertPasswordsAreIdentical($passwordAttributesTransfer)) {
            return $this->createPasswordsNotMatchError($restResponse);
        }

        $customerTransfer->fromArray($passwordAttributesTransfer->toArray(), true);

        $customerResponseTransfer = $this->customerClient->updateCustomerPassword($customerTransfer);
        if (!$customerResponseTransfer->getIsSuccess()) {
            foreach ($customerResponseTransfer->getErrors() as $error) {
                if ($error === static::ERROR_CUSTOMER_PASSWORD_INVALID) {
                    $restErrorTransfer = (new RestErrorMessageTransfer())
                        ->setStatus(Response::HTTP_BAD_REQUEST)
                        ->setCode(CustomersRestApiConfig::RESPONSE_CODE_INVALID_PASSWORD)
                        ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_INVALID_PASSWORD);
                } else {
                    $restErrorTransfer = (new RestErrorMessageTransfer())
                        ->setStatus(Response::HTTP_BAD_REQUEST)
                        ->setCode(CustomersRestApiConfig::RESPONSE_CODE_PASSWORD_CHANGE_FAILED)
                        ->setDetail($error->getMessage());
                }

                $restResponse->addError($restErrorTransfer);
            }

            return $restResponse;
        }

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_CUSTOMER_PASSWORD
        );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
     *
     * @return bool
     */
    protected function assertPasswordsAreIdentical(RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer): bool
    {
        return strcmp($passwordAttributesTransfer->getNewPassword(), $passwordAttributesTransfer->getConfirmPassword()) === 0;
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

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCustomerReferenceMissingError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_REFERENCE_MISSING)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_REFERENCE_MISSING);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCustomerNotFoundError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_NOT_FOUND)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_NOT_FOUND);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createPasswordsNotMatchError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_PASSWORDS_DONT_MATCH)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_PASSWORDS_DONT_MATCH);

        return $restResponse->addError($restErrorTransfer);
    }
}
