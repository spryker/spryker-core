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
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CategoriesRestApiReader implements CategoriesRestApiReaderInterface
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
     * @var \Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface
     */
    protected $categoriesResourceMapper;

    /**
     * CategoriesRestApiReader constructor.
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface $categoryStorageClient
     * @param \Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface $categoriesResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        CategoriesRestApiToCategoryStorageClientInterface $categoryStorageClient,
        CategoriesResourceMapperInterface $categoriesResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->categoryStorageClient = $categoryStorageClient;
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
        $categoriesTransfer = $this->categoriesResourceMapper
            ->mapCategoriesResourceToRestCategoriesTransfer($categoriesResource);

        $restResponse = $this->restResourceBuilder->createRestResponse();
        return $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                CategoriesRestApiConfig::RESOURCE_CATEGORIES,
                null,
                $categoriesTransfer
            )
        );
    }

    /**
     * @param int $nodeId
     * @param string $locale
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function readCategory(int $nodeId, string $locale): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $categoryResource = $this->categoryStorageClient->getCategoryNodeById($nodeId, $locale);

        if (empty($categoryResource->getNodeId())) {
            return $this->createErrorResponse($restResponse);
        }

        $categoryTransfer = (new RestCategoryNodesAttributesTransfer())
            ->fromArray(
                $categoryResource->toArray(),
                true
            );

        return $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                CategoriesRestApiConfig::RESOURCE_CATEGORY,
                $nodeId,
                $categoryTransfer
            )
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createErrorResponse(RestResponseInterface $restResponse)
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(CategoriesRestApiConfig::RESPONSE_CODE_INVALID_CATEGORY_ID)
            ->setStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->setDetail(CategoriesRestApiConfig::RESPONSE_DETAILS_INVALID_CATEGORY_ID);

        return $restResponse->addError($restErrorTransfer);
    }
}
