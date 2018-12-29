<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi\Processor\Navigation;

use Generated\Shared\Transfer\NavigationStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface;
use Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig;
use Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface;
use Symfony\Component\HttpFoundation\Response;

class NavigationReader implements NavigationReaderInterface
{
    /**
     * @var \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface
     */
    protected $navigationStorageClient;

    /**
     * @var \Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface
     */
    protected $navigationMapper;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface $navigationStorageClient
     * @param \Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface $navigationMapper
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(
        NavigationsRestApiToNavigationStorageClientInterface $navigationStorageClient,
        NavigationMapperInterface $navigationMapper,
        RestResourceBuilderInterface $restResourceBuilder
    ) {
        $this->navigationStorageClient = $navigationStorageClient;
        $this->navigationMapper = $navigationMapper;
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getNavigationTreeByKey(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $navigationTreeKey = $restRequest->getResource()->getId();
        if (!$navigationTreeKey) {
            return $restResponse->addError($this->createNavigationTreeKeyMissingError());
        }

        $restResource = $this->findNavigationTreeByKey($restRequest);
        if (!$restResource) {
            return $restResponse->addError($this->createNavigationTreeNotFoundError());
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findNavigationTreeByKey(RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $navigationStorageTransfer = $this->navigationStorageClient->findNavigationTreeByKey(
            $restRequest->getResource()->getId(),
            $restRequest->getMetadata()->getLocale()
        );

        if (!$navigationStorageTransfer || !$navigationStorageTransfer->getKey()) {
            return null;
        }

        return $this->buildNavigationTreeResource($restRequest->getResource()->getId(), $navigationStorageTransfer);
    }

    /**
     * @param string $navigationTreeKey
     * @param \Generated\Shared\Transfer\NavigationStorageTransfer $navigationStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildNavigationTreeResource(
        string $navigationTreeKey,
        NavigationStorageTransfer $navigationStorageTransfer
    ): RestResourceInterface {
        $restNavigationTreeAttributesTransfer = $this->navigationMapper
            ->mapNavigationStorageTransferToRestNavigationTreeAttributesTransfer($navigationStorageTransfer);

        return $this->restResourceBuilder->createRestResource(
            NavigationsRestApiConfig::RESOURCE_NAVIGATION_TREES,
            $navigationTreeKey,
            $restNavigationTreeAttributesTransfer
        );
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createNavigationTreeKeyMissingError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(NavigationsRestApiConfig::RESPONSE_CODE_NAVIGATION_TREE_KEY_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(NavigationsRestApiConfig::RESPONSE_DETAILS_NAVIGATION_TREE_KEY_IS_NOT_SPECIFIED);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createNavigationTreeNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(NavigationsRestApiConfig::RESPONSE_CODE_NAVIGATION_TREE_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(NavigationsRestApiConfig::RESPONSE_DETAILS_NAVIGATION_TREE_NOT_FOUND);

        return $restErrorTransfer;
    }
}
