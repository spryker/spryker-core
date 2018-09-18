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
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\CustomersRestApiErrorsTrait;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomersResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomersWriter implements CustomersWriterInterface
{
    use CustomersRestApiErrorsTrait;

    protected const ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED = 'customer.email.already.used';
    protected const ERROR_CUSTOMER_PASSWORD_INVALID = 'customer.password.invalid';

    protected const CUSTOMERS_GENDER_ENUM_MALE = 'Male';
    protected const CUSTOMERS_GENDER_ENUM_FEMALE = 'Female';
    protected const CUSTOMERS_GENDERS_ENUM = [
        self::CUSTOMERS_GENDER_ENUM_MALE,
        self::CUSTOMERS_GENDER_ENUM_FEMALE,
    ];

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
    public function registerCustomer(
        RestCustomersAttributesTransfer $restCustomersAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restCustomersAttributesTransfer->getAcceptedTerms()) {
            return $this->addNotAcceptedTermsError($restResponse);
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

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getResource()->getId());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerResponseTransfer->getHasCustomer()) {
            return $this->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->addCustomerUnauthorizedError($restResponse);
        }

        $customerResponseTransfer->getCustomerTransfer()->fromArray(
            $this->getCustomerData($restCustomerTransfer)
        );

        if ($customerResponseTransfer->getCustomerTransfer()->isPropertyModified(CustomerTransfer::GENDER) &&
            !in_array($customerResponseTransfer->getCustomerTransfer()->getGender(), static::CUSTOMERS_GENDERS_ENUM)) {
            return $this->addNotValidGenderError($restResponse);
        }

        $customerResponseTransfer = $this->customerClient->updateCustomer($customerResponseTransfer->getCustomerTransfer());

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $this->addCustomerNotSavedError($restResponse);
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
    public function updateCustomerPassword(
        RestRequestInterface $restRequest,
        RestCustomerPasswordAttributesTransfer $passwordAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $user = $restRequest->getUser();
        if (!$user) {
            return $this->addCustomerReferenceMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($user->getNaturalIdentifier());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);
        $customerTransfer = $customerResponseTransfer->getCustomerTransfer();

        if (!$customerTransfer) {
            return $this->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->addCustomerUnauthorizedError($restResponse);
        }

        if (!$this->assertPasswordNotEmpty($passwordAttributesTransfer)) {
            return $this->addPasswordNotValidError($restResponse);
        }

        if (!$this->assertPasswordsAreIdentical($passwordAttributesTransfer)) {
            return $this->addPasswordsNotMatchError($restResponse);
        }

        $customerTransfer->fromArray($passwordAttributesTransfer->toArray(), true);

        $customerResponseTransfer = $this->customerClient->updateCustomerPassword($customerTransfer);
        if (!$customerResponseTransfer->getIsSuccess()) {
            foreach ($customerResponseTransfer->getErrors() as $error) {
                if ($error->getMessage() === static::ERROR_CUSTOMER_PASSWORD_INVALID) {
                    return $this->addPasswordNotValidError($restResponse);
                }

                $this->addPasswordChangeError($restResponse, $error->getMessage());
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
            return $this->addCustomerReferenceMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getResource()->getId());
        $customerTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        if (!$customerTransfer->getHasCustomer()) {
            return $this->addCustomerNotFoundError($restResponse);
        }

        if (!$this->isSameCustomerReference($restRequest)) {
            return $this->addCustomerUnauthorizedError($restResponse);
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
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $response
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorResponse(CustomerResponseTransfer $customerResponseTransfer, RestResponseInterface $response): RestResponseInterface
    {
        foreach ($customerResponseTransfer->getErrors() as $error) {
            if ($error->getMessage() === static::ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED) {
                return $this->addCustomerAlreadyExistsError($response);
            }
            return $this->addCustomerCantRegisterCustomerMessageError($response, $error->getMessage());
        }

        return $response;
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
