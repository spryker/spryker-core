<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Builder;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestReturnItemsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnsAttributesTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class RestReturnResponseBuilder implements RestReturnResponseBuilderInterface
{
    protected const FORMAT_SELF_LINK_RETURN_ITEMS_RESOURCE = '%s/%s/%s/%s';

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface
     */
    protected $returnResourceMapper;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig
     */
    protected $salesReturnsRestApiConfig;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\SalesReturnsRestApi\Processor\Mapper\ReturnResourceMapperInterface $returnResourceMapper
     * @param \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig $salesReturnsRestApiConfig
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ReturnResourceMapperInterface $returnResourceMapper,
        SalesReturnsRestApiConfig $salesReturnsRestApiConfig
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->returnResourceMapper = $returnResourceMapper;
        $this->salesReturnsRestApiConfig = $salesReturnsRestApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnListRestResponse(
        ReturnFilterTransfer $returnFilterTransfer,
        ReturnCollectionTransfer $returnCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                $returnCollectionTransfer->getPagination()->getNbResults(),
                $returnFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($returnCollectionTransfer->getReturns() as $returnTransfer) {
            $restReturnsAttributesTransfer = $this->returnResourceMapper
                ->mapReturnTransferToRestReturnsAttributesTransfer($returnTransfer, new RestReturnsAttributesTransfer());

            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    SalesReturnsRestApiConfig::RESOURCE_RETURNS,
                    $restReturnsAttributesTransfer->getReturnReference(),
                    $restReturnsAttributesTransfer
                )->setPayload($returnTransfer)
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnRestResponse(ReturnTransfer $returnTransfer): RestResponseInterface
    {
        $restReturnsAttributesTransfer = $this->returnResourceMapper
            ->mapReturnTransferToRestReturnsAttributesTransfer($returnTransfer, new RestReturnsAttributesTransfer());

        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                SalesReturnsRestApiConfig::RESOURCE_RETURNS,
                $restReturnsAttributesTransfer->getReturnReference(),
                $restReturnsAttributesTransfer
            )->setPayload($returnTransfer)
        );

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

        return $this->addRestResponseError($restResponse, $message);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnResponseTransfer $returnResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorRestResponseFromReturnResponse(
        ReturnResponseTransfer $returnResponseTransfer
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $errorIdentifiers = $this->mapReturnResponseTransferToErrorIdentifiers($returnResponseTransfer);

        foreach ($errorIdentifiers as $errorIdentifier) {
            $restResponse = $this->addRestResponseError($restResponse, $errorIdentifier);
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnResponseTransfer $returnResponseTransfer
     *
     * @return string[]
     */
    protected function mapReturnResponseTransferToErrorIdentifiers(ReturnResponseTransfer $returnResponseTransfer): array
    {
        $errorMessageToErrorIdentifierMapping = $this->salesReturnsRestApiConfig
            ->getErrorMessageToErrorIdentifierMapping();

        $errorIdentifiers = [];
        foreach ($returnResponseTransfer->getMessages() as $messageTransfer) {
            $errorMessage = $messageTransfer->getValue();
            if (array_key_exists($errorMessage, $errorMessageToErrorIdentifierMapping)) {
                $errorIdentifiers[] = $errorMessageToErrorIdentifierMapping[$errorMessage];

                continue;
            }
            $errorIdentifiers[] = $this->salesReturnsRestApiConfig->getDefaultErrorMessageIdentifier();
        }

        return array_unique($errorIdentifiers);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createReturnItemRestResourcesFromReturnTransfer(ReturnTransfer $returnTransfer): array
    {
        $restResources = [];
        $returnReference = $returnTransfer->getReturnReference();

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $restResources[] = $this->createReturnItemRestResource(
                $returnItemTransfer,
                $returnReference
            );
        }

        return $restResources;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnItemTransfer $returnItemTransfer
     * @param string $returnReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createReturnItemRestResource(ReturnItemTransfer $returnItemTransfer, string $returnReference): RestResourceInterface
    {
        $restReturnItemsAttributesTransfer = $this->returnResourceMapper
            ->mapReturnItemTransferToRestReturnItemsAttributesTransfer($returnItemTransfer, new RestReturnItemsAttributesTransfer());

        $returnItemResource = $this->restResourceBuilder->createRestResource(
            SalesReturnsRestApiConfig::RESOURCE_RETURN_ITEMS,
            $returnItemTransfer->getUuid(),
            $restReturnItemsAttributesTransfer
        );

        $returnItemResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->createSelfLinkForReturnItem($returnReference, $returnItemTransfer->getUuid())
        );

        return $returnItemResource;
    }

    /**
     * @param string $idReturn
     * @param string $idReturnItem
     *
     * @return string
     */
    protected function createSelfLinkForReturnItem(string $idReturn, string $idReturnItem): string
    {
        return sprintf(
            static::FORMAT_SELF_LINK_RETURN_ITEMS_RESOURCE,
            SalesReturnsRestApiConfig::RESOURCE_RETURNS,
            $idReturn,
            SalesReturnsRestApiConfig::RESOURCE_RETURN_ITEMS,
            $idReturnItem
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param string $message
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addRestResponseError(RestResponseInterface $restResponse, string $message)
    {
        $restResponse->addError(
            $this->returnResourceMapper->mapMessageTransferToRestErrorMessageTransfer(
                (new MessageTransfer())->setValue($message),
                new RestErrorMessageTransfer()
            )
        );

        return $restResponse;
    }
}
