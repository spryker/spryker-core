<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomerAccessRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestCustomerAccessAttributesTransfer;
use Spryker\Glue\CustomerAccessRestApi\CustomerAccessRestApiConfig;
use Spryker\Glue\CustomerAccessRestApi\Processor\Mapper\CustomerAccessMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class CustomerAccessRestResponseBuilder implements CustomerAccessRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CustomerAccessRestApi\Processor\Mapper\CustomerAccessMapperInterface
     */
    protected $customerAccessMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CustomerAccessRestApi\Processor\Mapper\CustomerAccessMapperInterface $customerAccessMapper
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder, CustomerAccessMapperInterface $customerAccessMapper)
    {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->customerAccessMapper = $customerAccessMapper;
    }

    /**
     * @param array $customerAccessContentTypeResourceTypes
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCustomerAccessResponse(array $customerAccessContentTypeResourceTypes): RestResponseInterface
    {
        $restCustomerAccessAttributesTransfer = $this->customerAccessMapper
            ->mapCustomerAccessContentTypeResourceTypeToRestCustomerAccessAttributesTransfer(
                $customerAccessContentTypeResourceTypes,
                new RestCustomerAccessAttributesTransfer()
            );

        $restResource = $this->restResourceBuilder->createRestResource(
            CustomerAccessRestApiConfig::RESOURCE_CUSTOMER_ACCESS,
            null,
            $restCustomerAccessAttributesTransfer
        );

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($restResource);
    }
}
