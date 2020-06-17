<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Generated\Shared\Transfer\RestContentProductAbstractListAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

class ContentProductAbstractListRestResponseBuilder implements ContentProductAbstractListRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface
     */
    protected $productsRestApiResource;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ContentProductAbstractListsRestApi\Dependency\RestApiResource\ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ContentProductAbstractListsRestApiToProductsRestApiResourceInterface $productsRestApiResource
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productsRestApiResource = $productsRestApiResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentItemIdNotSpecifiedError(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_KEY_IS_MISSING)
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_KEY_IS_MISSING)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentItemtNotFoundError(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_NOT_FOUND)
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_NOT_FOUND)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentTypeInvalidError(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setCode(ContentProductAbstractListsRestApiConfig::RESPONSE_CODE_CONTENT_TYPE_INVALID)
                    ->setStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
                    ->setDetail(ContentProductAbstractListsRestApiConfig::RESPONSE_DETAILS_CONTENT_TYPE_INVALID)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentProductAbstractListsRestResponse(
        ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $idProductAbstracts = $contentProductAbstractListTypeTransfer->getIdProductAbstracts();
        foreach ($idProductAbstracts as $idProductAbstract) {
            $abstractProductResource = $this->productsRestApiResource->findProductAbstractById($idProductAbstract, $restRequest);

            $restResponse->addResource($abstractProductResource);
        }

        return $restResponse;
    }

    /**
     * @phpstan-param array<string, array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer>> $mappedContentProductAbstractListTypeTransfers
     *
     * @phpstan-return array<string, array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     *
     * @param array[] $mappedContentProductAbstractListTypeTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array[]
     */
    public function createContentProductAbstractListsRestResources(
        array $mappedContentProductAbstractListTypeTransfers,
        RestRequestInterface $restRequest
    ): array {
        $contentProductAbstractListsRestResources = [];
        foreach ($mappedContentProductAbstractListTypeTransfers as $cmsPageUuid => $contentProductAbstractListTypeTransfers) {
            foreach ($contentProductAbstractListTypeTransfers as $contentProductAbstractListKey => $contentProductAbstractListTypeTransfer) {
                $contentProductAbstractListsRestResources[$cmsPageUuid][$contentProductAbstractListKey] = $this->createContentProductAbstractListsRestResource(
                    $contentProductAbstractListTypeTransfer,
                    $restRequest,
                    $contentProductAbstractListKey
                );
            }
        }

        return $contentProductAbstractListsRestResources;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $contentProductAbstractListKey
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createContentProductAbstractListsRestResource(
        ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer,
        RestRequestInterface $restRequest,
        string $contentProductAbstractListKey
    ): RestResourceInterface {
        $idProductAbstracts = $contentProductAbstractListTypeTransfer->getIdProductAbstracts();

        $restContentProductAbstractListAttributesTransfer = new RestContentProductAbstractListAttributesTransfer();
        foreach ($idProductAbstracts as $idProductAbstract) {
            /** @var \Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer $abstractProduct */
            $abstractProduct = $this->productsRestApiResource->findProductAbstractById($idProductAbstract, $restRequest)->getAttributes();
            $restContentProductAbstractListAttributesTransfer->addAbstractProduct(
                $abstractProduct
            );
        }

        return $this->restResourceBuilder->createRestResource(
            ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS,
            $contentProductAbstractListKey,
            $restContentProductAbstractListAttributesTransfer
        );
    }
}
