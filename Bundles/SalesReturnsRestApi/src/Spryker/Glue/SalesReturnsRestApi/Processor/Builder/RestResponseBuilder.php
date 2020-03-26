<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Builder;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class RestResponseBuilder implements RestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface
     */
    protected $returnResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface $returnResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ReturnResourceMapperInterface $returnResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->returnResourceMapper = $returnResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     * @param array $restReturnsAttributesTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnListRestResponse(
        ReturnFilterTransfer $returnFilterTransfer,
        array $restReturnsAttributesTransfers,
        PaginationTransfer $paginationTransfer
    ): RestResponseInterface {
        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                $paginationTransfer->getNbResults(),
                $returnFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($restReturnsAttributesTransfers as $restReturnsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURNS,
                    $restReturnsAttributesTransfer->getReturnReference(),
                    $restReturnsAttributesTransfer
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnDetailRestResponse(RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                SalesReturnsRestApiConfig::RESOURCE_RETURNS,
                $restReturnDetailsAttributesTransfer->getReturnReference(),
                $restReturnDetailsAttributesTransfer
            )
        );

        return $restResponse;
    }

    /**
     * @param array $restOrderItemsAttributesTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnableItemListRestResponse(array $restOrderItemsAttributesTransfers): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        foreach ($restOrderItemsAttributesTransfers as $restOrderItemsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURNABLE_ITEMS,
                    $restOrderItemsAttributesTransfer->getUuid(),
                    $restOrderItemsAttributesTransfer
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnableItemDetailRestResponse(RestOrderItemsAttributesTransfer $restOrderItemsAttributesTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                SalesReturnsRestApiConfig::RESOURCE_RETURNABLE_ITEMS,
                $restOrderItemsAttributesTransfer->getUuid(),
                $restOrderItemsAttributesTransfer
            )
        );

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     * @param array $restReturnReasonsAttributesTransfers
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnReasonListRestResponse(
        ReturnReasonFilterTransfer $returnReasonFilterTransfer,
        array $restReturnReasonsAttributesTransfers,
        PaginationTransfer $paginationTransfer
    ): RestResponseInterface {
        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                $paginationTransfer->getNbResults(),
                $returnReasonFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($restReturnReasonsAttributesTransfers as $restReturnReasonsAttributesTransfer) {
            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURN_REASONS,
                    null,
                    $restReturnReasonsAttributesTransfer
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param string $message
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorRestResponse(string $message): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addError(
            $this->returnResourceMapper->mapMessageTransferToRestErrorMessageTransfer(
                (new MessageTransfer())->setValue($message),
                new RestErrorMessageTransfer()
            )
        );

        return $restResponse;
    }
}
