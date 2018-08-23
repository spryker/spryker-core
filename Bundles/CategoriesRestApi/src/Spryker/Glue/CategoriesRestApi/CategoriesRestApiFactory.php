<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesReader;
use Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesReaderInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapper;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface getResourceBuilder()
 */
class CategoriesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesReaderInterface
     */
    public function createCategoriesReader(): CategoriesReaderInterface
    {
        return new CategoriesReader(
            $this->getResourceBuilder(),
            $this->getCategoryStorageClient(),
            $this->getProductCategoryResourceAliasStorageClient(),
            $this->createCategoriesResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface
     */
    protected function getCategoryStorageClient(): CategoriesRestApiToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CategoriesRestApiDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface
     */
    protected function getProductCategoryResourceAliasStorageClient(): CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface
    {
        return $this->getProvidedDependency(CategoriesRestApiDependencyProvider::CLIENT_PRODUCT_CATEGORY_RESOURCE_ALIAS_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface
     */
    protected function createCategoriesResourceMapper(): CategoriesResourceMapperInterface
    {
        return new CategoriesResourceMapper();
    }
}
