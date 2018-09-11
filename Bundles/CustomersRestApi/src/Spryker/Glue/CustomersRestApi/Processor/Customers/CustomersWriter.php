<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RestCustomersRegisterAttributesTransfer;
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
     * @param \Generated\Shared\Transfer\RestCustomersRegisterAttributesTransfer $restCustomersAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function registerCustomer(RestCustomersRegisterAttributesTransfer $restCustomersAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restCustomersAttributesTransfer->getAcceptedTerms()) {
            return $this->createNotAcceptedTermsError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())->fromArray($restCustomersAttributesTransfer->toArray(), true);
        $customerResponseTransfer = $this->customerClient->registerCustomer($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $this->createErrorResponse($customerResponseTransfer, $restResponse);
        }

        $restResource = $this->customersResourceMapper->mapCustomerTransferToRestResource($customerResponseTransfer->getCustomerTransfer());

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $restCustomerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateCustomer(
        RestRequestInterface $restRequest,
        RestCustomersAttributesTransfer $restCustomerTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->createUnauthorizedError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer)
            ->setCustomerReference($restRequest->getResource()->getId());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->createCustomerNotFoundError($restResponse);
        }

        $customerResponseTransfer->getCustomerTransfer()->fromArray(
            $this->getCustomerData($restCustomerTransfer)
        );
        $customerResponseTransfer = $this->customerClient->updateCustomer($customerResponseTransfer->getCustomerTransfer());

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $this->createCustomerNotSavedError($restResponse);
        }

        $restResource = $this
            ->customersResourceMapper
            ->mapCustomerTransferToRestResource($customerResponseTransfer->getCustomerTransfer());

        return $restResponse->addResource($restResource);
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

        $customerResponseTransfer = $this->customersReader->findCustomerByReference($user->getNaturalIdentifier());
        $customerTransfer = $customerResponseTransfer->getCustomerTransfer();

        if (!$customerTransfer) {
            return $this->createCustomerNotFoundError($restResponse);
        }

        if (!$this->assertPasswordsAreIdentical($passwordAttributesTransfer)) {
            return $this->createPasswordsNotMatchError($restResponse);
        }

        if (!$this->assertPasswordNotEmpty($passwordAttributesTransfer)) {
            return $this->createPasswordNotValid($restResponse);
        }

        $customerTransfer->fromArray($passwordAttributesTransfer->toArray(), true);

        $customerResponseTransfer = $this->customerClient->updateCustomerPassword($customerTransfer);
        if (!$customerResponseTransfer->getIsSuccess()) {
            foreach ($customerResponseTransfer->getErrors() as $error) {
                if ($error->getMessage() === static::ERROR_CUSTOMER_PASSWORD_INVALID) {
                    return $this->createPasswordNotValid($restResponse);
                }

                $restErrorTransfer = (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode(CustomersRestApiConfig::RESPONSE_CODE_PASSWORD_CHANGE_FAILED)
                    ->setDetail($error->getMessage());

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
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function anonymizeCustomer(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->createCustomerReferenceMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer)
            ->setCustomerReference($restRequest->getResource()->getId());
        $customerTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerTransfer->getHasCustomer()) {
            return $this->createCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->createUnauthorizedError($restResponse);
        }

        $this->customerClient->anonymizeCustomer($customerTransfer->getCustomerTransfer());

        return $restResponse;
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
     * @param \Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
     *
     * @return bool
     */
    protected function assertPasswordNotEmpty(RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer): bool
    {
        return mb_strlen($passwordAttributesTransfer->getNewPassword()) > 1;
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

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createPasswordNotValid(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_INVALID_PASSWORD)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_INVALID_PASSWORD);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createCustomerNotSavedError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_FAILED_TO_SAVE)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_FAILED_TO_SAVE);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createUnauthorizedError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_CUSTOMER_UNAUTHORIZED)
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_CUSTOMER_UNAUTHORIZED);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createNotAcceptedTermsError(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CustomersRestApiConfig::RESPONSE_CODE_NOT_ACCEPTED_TERMS)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(CustomersRestApiConfig::RESPONSE_DETAILS_NOT_ACCEPTED_TERMS);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function isSameCustomerReference(RestRequestInterface $restRequest): bool
    {
        return $restRequest->getUser()->getNaturalIdentifier() === $restRequest->getResource()->getId();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $customerTransfer
     *
     * @return array
     */
    protected function getCustomerData(RestCustomersAttributesTransfer $customerTransfer): array
    {
        $customerData = $customerTransfer->modifiedToArray(true, true);

        return $this->cleanUpCustomerAttributes($customerData);
    }

    /**
     * @param array $customerAttributes
     *
     * @return array
     */
    protected function cleanUpCustomerAttributes(array $customerAttributes): array
    {
        unset($customerAttributes[RestCustomersAttributesTransfer::CREATED_AT]);
        unset($customerAttributes[RestCustomersAttributesTransfer::UPDATED_AT]);
        unset($customerAttributes[RestCustomersAttributesTransfer::REGISTERED]);

        return $customerAttributes;
    }
}
