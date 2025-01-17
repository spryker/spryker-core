<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OrderAmendmentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestOrderAmendmentsAttributesTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\OrderAmendmentsRestApi\OrderAmendmentsRestApiConfig;
use Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\OrderAmendmentsMapperInterface;

class OrderAmendmentsRestResponseBuilder implements OrderAmendmentsRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @var \Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\OrderAmendmentsMapperInterface
     */
    protected OrderAmendmentsMapperInterface $orderAmendmentsMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\OrderAmendmentsRestApi\Processor\Mapper\OrderAmendmentsMapperInterface $orderAmendmentsMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        OrderAmendmentsMapperInterface $orderAmendmentsMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->orderAmendmentsMapper = $orderAmendmentsMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createOrderAmendmentRestResource(
        SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
    ): RestResourceInterface {
        $restOrderAmendmentsAttributesTransfer = $this->orderAmendmentsMapper
            ->mapSalesOrderAmendmentTransferToRestOrderAmendmentsAttributesTransfer(
                $salesOrderAmendmentTransfer,
                new RestOrderAmendmentsAttributesTransfer(),
            );

        return $this->restResourceBuilder->createRestResource(
            OrderAmendmentsRestApiConfig::RESOURCE_ORDER_AMENDMENTS,
            $salesOrderAmendmentTransfer->getUuid(),
            $restOrderAmendmentsAttributesTransfer,
        );
    }
}
