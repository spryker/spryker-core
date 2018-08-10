<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomersWriter implements CustomersWriterInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomerRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        CustomerRestApiToCustomerClientInterface $customerClient,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->customerClient = $customerClient;
        $this->restResourceBuilder = $restResourceBuilder;
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
}
