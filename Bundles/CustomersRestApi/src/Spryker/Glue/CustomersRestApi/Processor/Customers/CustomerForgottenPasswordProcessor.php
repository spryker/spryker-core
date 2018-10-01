<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface;
use Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class CustomerForgottenPasswordProcessor implements CustomerForgottenPasswordProcessorInterface
{
    /**
     * @var \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapperInterface
     */
    protected $customerForgottenPasswordResourceMapper;

    /**
     * @param \Spryker\Glue\CustomersRestApi\Dependency\Client\CustomersRestApiToCustomerClientInterface $customerClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomersRestApi\Processor\Mapper\CustomerForgottenPasswordResourceMapperInterface $customerForgottenPasswordResourceMapper
     */
    public function __construct(
        CustomersRestApiToCustomerClientInterface $customerClient,
        RestResourceBuilderInterface $restResourceBuilder,
        CustomerForgottenPasswordResourceMapperInterface $customerForgottenPasswordResourceMapper
    ) {
        $this->customerClient = $customerClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerForgottenPasswordResourceMapper = $customerForgottenPasswordResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function sendPasswordRestoreMail(RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse();

        $customerTransfer = $this->customerForgottenPasswordResourceMapper->mapCustomerForgottenPasswordAttributesToCustomerTransfer($restCustomerForgottenPasswordAttributesTransfer);

        if ($this->customerClient->findCustomerWithEmailAndPassword($customerTransfer)) {
            $this->customerClient->sendPasswordRestoreMail($customerTransfer);
        }

        $restResource = $this->restResourceBuilder->createRestResource(CustomersRestApiConfig::RESOURCE_FORGOTTEN_PASSWORD);

        return $response->addResource($restResource);
    }
}
