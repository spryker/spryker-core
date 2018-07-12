<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersAttributesTransfer;
use Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CustomersResourceMapper implements CustomersResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function mapCustomerAttributesToCustomerTransfer(RestRegisterCustomerAttributesTransfer $restRegisterCustomerAttributesTransfer): CustomerTransfer
    {
        return (new CustomerTransfer())->fromArray($restRegisterCustomerAttributesTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapCustomerToCustomersRestResource(CustomerTransfer $customerTransfer): RestResourceInterface
    {
        $restRegisterCustomerAttributesTransfer = (new RestCustomersAttributesTransfer())->fromArray($customerTransfer->toArray(), true);
        return $this->restResourceBuilder->createRestResource(
            CustomersRestApiConfig::RESOURCE_REGISTER_CUSTOMERS,
            (string)$customerTransfer->getIdCustomer(),
            $restRegisterCustomerAttributesTransfer
        );
    }
}
