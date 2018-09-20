<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customer;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorsInterface;
use Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorsInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerResourceMapperInterface $customersResourceMapper
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiErrorsInterface $restApiErrors
     * @param \Spryker\Glue\CustomersRestApi\Processor\Validation\RestApiValidatorsInterface $restApiValidators
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CustomersRestApiToCustomerClientInterface $customerClient,
        CustomerResourceMapperInterface $customersResourceMapper,
        RestApiErrorsInterface $restApiErrors,
        RestApiValidatorsInterface $restApiValidators
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerClient = $customerClient;
        $this->customersResourceMapper = $customersResourceMapper;
        $this->restApiErrors = $restApiErrors;
        $this->restApiValidators = $restApiValidators;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerByCustomerReference(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        if (!$restRequest->getResource()->getId()) {
            return $this->restApiErrors->addCustomerReferenceMissingError($restResponse);
        }

        $customerTransfer = (new CustomerTransfer())->setCustomerReference($restRequest->getResource()->getId());
        $customerResponseTransfer = $this->customerClient->findCustomerByReference($customerTransfer);

        $restResponse = $this->restApiValidators->validateCustomerResponseTransfer(
            $customerResponseTransfer,
            $restRequest,
            $restResponse
        );

        if (count($restResponse->getErrors()) > 0) {
            return $restResponse;
        }

        $customersResource = $this
            ->customersResourceMapper
            ->mapCustomerTransferToRestResource($customerResponseTransfer->getCustomerTransfer());
        $restResponse->addResource($customersResource);

        return $restResponse;
    }
}
