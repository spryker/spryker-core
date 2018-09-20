<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomerPasswordAttributesTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorsInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorsInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerWriter implements CustomerWriterInterface
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
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface
     */
    protected $customersResourceMapper;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorsInterface
     */
    protected $restApiErrors;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorsInterface
     */
    protected $restApiValidators;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface $customersResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorsInterface $restApiErrors
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorsInterface $restApiValidators
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CustomerResourceMapperInterface $customersResourceMapper,
        RestApiErrorsInterface $restApiErrors,
        RestApiValidatorsInterface $restApiValidators
    ) {
        $this->customerClient = $customerClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customersResourceMapper = $customersResourceMapper;
        $this->restApiErrors = $restApiErrors;
        $this->restApiValidators = $restApiValidators;
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
            return $this->restApiErrors->addNotAcceptedTermsError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())->fromArray($restCustomersAttributesTransfer->toArray(), true);
        $customerResponseTransfer = $this->customerClient->registerCustomer($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess()) {
            foreach ($customerResponseTransfer->getErrors() as $error) {
                if ($error->getMessage() === static::ERROR_MESSAGE_CUSTOMER_EMAIL_ALREADY_USED) {
                    return $this->restApiErrors->addCustomerAlreadyExistsError($restResponse);
                }
                return $this->restApiErrors->addCustomerCantRegisterMessageError($restResponse, $error->getMessage());
            }
        }

        $restResource = $this->customersResourceMapper->mapCustomerTransferToRestResource($customerResponseTransfer->getCustomerTransfer());

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestCustomersAttributesTransfer $restCustomerAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateCustomer(
        RestRequestInterface $restRequest,
        RestCustomersAttributesTransfer $restCustomerAttributesTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getResource()->getId());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        $restResponse = $this->restApiValidators->validateCustomerResponseTransfer(
            $customerResponseTransfer,
            $restRequest,
            $restResponse
        );

        $restResponse = $this->restApiValidators->validateCustomerGender($restCustomerAttributesTransfer, $restResponse);

        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        $customerResponseTransfer->getCustomerTransfer()->fromArray(
            $this->getCustomerData($restCustomerAttributesTransfer)
        );

        $customerResponseTransfer = $this->customerClient->updateCustomer($customerResponseTransfer->getCustomerTransfer());

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $this->restApiErrors->addCustomerNotSavedError($restResponse);
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

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($user->getNaturalIdentifier());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        $restResponse = $this->restApiValidators->validateCustomerResponseTransfer(
            $customerResponseTransfer,
            $restRequest,
            $restResponse
        );

        $restResponse = $this->restApiValidators->validatePassword($passwordAttributesTransfer, $restResponse);

        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        $customerTransfer = $customerResponseTransfer->getCustomerTransfer();
        $customerTransfer->fromArray($passwordAttributesTransfer->toArray(), true);

        $customerResponseTransfer = $this->customerClient->updateCustomerPassword($customerTransfer);

        foreach ($customerResponseTransfer->getErrors() as $error) {
            if ($error->getMessage() === static::ERROR_CUSTOMER_PASSWORD_INVALID) {
                return $this->restApiErrors->addPasswordNotValidError($restResponse);
            }

            return $this->restApiErrors->addPasswordChangeError($restResponse, $error->getMessage());
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
            return $this->restApiErrors->addCustomerReferenceMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getResource()->getId());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        $restResponse = $this->restApiValidators->validateCustomerResponseTransfer(
            $customerResponseTransfer,
            $restRequest,
            $restResponse
        );

        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        $this->customerClient->anonymizeCustomer($customerResponseTransfer->getCustomerTransfer());

        return $restResponse;
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
        unset(
            $customerAttributes[RestCustomersAttributesTransfer::CREATED_AT],
            $customerAttributes[RestCustomersAttributesTransfer::UPDATED_AT],
            $customerAttributes[RestCustomersAttributesTransfer::REGISTERED]
        );

        return $customerAttributes;
    }
}
