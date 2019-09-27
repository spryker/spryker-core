<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoryMapper;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoryMapperInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\RestUrlResolverAttributesMapper;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\RestUrlResolverAttributesMapperInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Reader\CategoryReader;
use Spryker\Glue\CategoriesRestApi\Processor\Reader\CategoryReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface getResourceBuilder()
 */
class CategoriesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Reader\CategoryReaderInterface
     */
    public function createCategoryReader(): CategoryReaderInterface
    {
        return new CategoryReader(
            $this->getResourceBuilder(),
            $this->getCategoryStorageClient(),
            $this->createCategoryMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface
     */
    public function getCategoryStorageClient(): CategoriesRestApiToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CategoriesRestApiDependencyProvider::CLIENT_CATEGORY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoryMapperInterface
     */
    public function createCategoryMapper(): CategoryMapperInterface
    {
        return new CategoryMapper();
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Mapper\RestUrlResolverAttributesMapperInterface
     */
    public function createRestUrlResolverAttributesMapper(): RestUrlResolverAttributesMapperInterface
    {
        return new RestUrlResolverAttributesMapper();
    }
}
