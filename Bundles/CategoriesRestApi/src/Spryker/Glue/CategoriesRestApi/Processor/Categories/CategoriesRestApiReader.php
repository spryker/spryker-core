<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Categories;

use Generated\Shared\Transfer\RestCategoryAttributesTransfer;
use Spryker\Glue\CategoriesRestApi\CategoriesRestApiConfig;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

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
        $categoryResource = $this->categoryStorageClient->getCategoryNodeById($nodeId, $locale);
        $categoryTransfer = (new RestCategoryAttributesTransfer())
            ->fromArray(
                $categoryResource->toArray(),
                true
            );

        $restResponse = $this->restResourceBuilder->createRestResponse();
        return $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                CategoriesRestApiConfig::RESOURCE_CATEGORY,
                null,
                $categoryTransfer
            )
        );
    }
}
