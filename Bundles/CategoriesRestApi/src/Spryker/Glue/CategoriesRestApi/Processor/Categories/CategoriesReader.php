<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Categories;

use Generated\Shared\Transfer\RestCategoryNodesAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\CategoriesRestApi\CategoriesRestApiConfig;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

use Symfony\Component\HttpFoundation\Response;

class CategoriesReader implements CategoriesReaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface
     */
    protected $categoryStorageClient;

    /**
     * @var \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface
     */
    protected $productCategoryResourceAliasStorageClient;

    /**
     * @var \Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface
     */
    protected $categoriesResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface $categoryStorageClient
     * @param \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface $productCategoryResourceAliasStorageClient
     * @param \Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface $categoriesResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CategoriesRestApiToCategoryStorageClientInterface $categoryStorageClient,
        CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface $productCategoryResourceAliasStorageClient,
        CategoriesResourceMapperInterface $categoriesResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->categoryStorageClient = $categoryStorageClient;
        $this->productCategoryResourceAliasStorageClient = $productCategoryResourceAliasStorageClient;
        $this->categoriesResourceMapper = $categoriesResourceMapper;
    }

    /**
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCategoriesTree(string $locale): RestResponseInterface
    {
        $categoriesResource = $this->categoryStorageClient->getCategories($locale);
        $restCategoriesTreeTransfer = $this->categoriesResourceMapper
            ->mapCategoriesResourceToRestCategoriesTransfer((array)$categoriesResource);

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $restResource = $this
            ->restResourceBuilder
            ->createRestResource(
                CategoriesRestApiConfig::RESOURCE_CATEGORY_TREES,
                null,
                $restCategoriesTreeTransfer
            );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getProductCategoriesResourceBySku(RestRequestInterface $restRequest): RestResourceInterface
    {
        /** @var string $abstractSku */
        $abstractSku = $restRequest->getResource()->getId();

        $productAbstractCategoryStorageTransfer = $this->productCategoryResourceAliasStorageClient
            ->findProductCategoryAbstractStorageTransfer(
                $abstractSku,
                $restRequest->getMetadata()->getLocale()
            );

        if (!$productAbstractCategoryStorageTransfer) {
            $restErrorTransfer = $this->createRestErrorTransfer();

            return $this->restResourceBuilder->createRestResource(
                CategoriesRestApiConfig::RESOURCE_PRODUCT_CATEGORIES,
                $abstractSku,
                $restErrorTransfer
            );
        }

        $categoriesTransfer = $this->categoriesResourceMapper
            ->mapProductCategoriesToRestProductCategoriesTransfer($productAbstractCategoryStorageTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            CategoriesRestApiConfig::RESOURCE_PRODUCT_CATEGORIES,
            $abstractSku,
            $categoriesTransfer
        );

        return $restResource;
    }

    /**
     * @param int $nodeId
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCategory(int $nodeId, string $locale): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $categoryNodeStorageTransfer = $this->categoryStorageClient->getCategoryNodeById($nodeId, $locale);

        if (empty($categoryNodeStorageTransfer->getNodeId())) {
            return $this->createErrorResponse($restResponse);
        }

        $restCategoryNodesAttributesTransfer = (new RestCategoryNodesAttributesTransfer())
            ->fromArray(
                $categoryNodeStorageTransfer->toArray(),
                true
            );

        $restResource = $this
            ->restResourceBuilder
            ->createRestResource(
                CategoriesRestApiConfig::RESOURCE_CATEGORY_NODES,
                (string)$restCategoryNodesAttributesTransfer->getNodeId(),
                $restCategoryNodesAttributesTransfer
            );

        return $restResponse->addResource($restResource);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorResponse(RestResponseInterface $restResponse): RestResponseInterface
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CategoriesRestApiConfig::RESPONSE_CODE_INVALID_CATEGORY_ID)
            ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setDetail(CategoriesRestApiConfig::RESPONSE_DETAILS_INVALID_CATEGORY_ID);

        return $restResponse->addError($restErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createRestErrorTransfer(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(CategoriesRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_CATEGORIES_ARE_MISSING)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(CategoriesRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_CATEGORIES_ARE_MISSING);
    }
}
