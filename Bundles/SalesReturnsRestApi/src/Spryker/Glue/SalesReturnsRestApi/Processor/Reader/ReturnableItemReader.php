<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class ReturnableItemReader implements ReturnableItemReaderInterface
{
    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface
     */
    protected $returnResourceMapper;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\Client\SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface $returnResourceMapper
     */
    public function __construct(
        SalesReturnsRestApiToSalesReturnClientInterface $salesReturnClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ReturnResourceMapperInterface $returnResourceMapper
    ) {
        $this->salesReturnClient = $salesReturnClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->returnResourceMapper = $returnResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getReturnableItems(RestRequestInterface $restRequest): RestResponseInterface
    {
        $returnableItemFilterTransfer = $this->createReturnableItemFilterTransfer($restRequest);
        $itemCollectionTransfer = $this->salesReturnClient->getReturnableItems($returnableItemFilterTransfer);

        $restOrderItemsAttributesTransfers = $this->returnResourceMapper
            ->mapItemCollectionTransferToRestOrderItemsAttributesTransfers($itemCollectionTransfer);

        return $this->createRestResponse($returnableItemFilterTransfer, $restOrderItemsAttributesTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer[] $restOrderItemsAttributesTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createRestResponse(
        ReturnableItemFilterTransfer $returnableItemFilterTransfer,
        array $restOrderItemsAttributesTransfers
    ): RestResponseInterface {
        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                count($restOrderItemsAttributesTransfers),
                $returnableItemFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($restOrderItemsAttributesTransfers as $restOrderItemsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURNABLE_ITEMS,
                    null,
                    $restOrderItemsAttributesTransfer
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ReturnableItemFilterTransfer
     */
    protected function createReturnableItemFilterTransfer(RestRequestInterface $restRequest): ReturnableItemFilterTransfer
    {
        $filterTransfer = new FilterTransfer();

        if ($restRequest->getPage()) {
            $filterTransfer
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit() ?? 0);
        }

        return (new ReturnableItemFilterTransfer())
            ->fromArray($restRequest->getHttpRequest()->query->all(), true)
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setFilter($filterTransfer);
    }
}
