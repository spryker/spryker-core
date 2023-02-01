<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi;

use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryImageFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToLocaleFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToStoreFacadeInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapper;
use Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapper;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapper;
use Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface;
use Spryker\Glue\CategoriesBackendApi\Processor\Creator\CategoryCreator;
use Spryker\Glue\CategoriesBackendApi\Processor\Creator\CategoryCreatorInterface;
use Spryker\Glue\CategoriesBackendApi\Processor\Deleter\CategoryDeleter;
use Spryker\Glue\CategoriesBackendApi\Processor\Deleter\CategoryDeleterInterface;
use Spryker\Glue\CategoriesBackendApi\Processor\Reader\CategoryReader;
use Spryker\Glue\CategoriesBackendApi\Processor\Reader\CategoryReaderInterface;
use Spryker\Glue\CategoriesBackendApi\Processor\Updater\CategoryUpdater;
use Spryker\Glue\CategoriesBackendApi\Processor\Updater\CategoryUpdaterInterface;
use Spryker\Glue\Kernel\Backend\AbstractFactory;

class CategoriesBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Mapper\GlueRequestCategoryMapperInterface
     */
    public function createGlueRequestCategoryMapper(): GlueRequestCategoryMapperInterface
    {
        return new GlueRequestCategoryMapper();
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Mapper\GlueResponseCategoryMapperInterface
     */
    public function createGlueResponseCategoryMapper(): GlueResponseCategoryMapperInterface
    {
        return new GlueResponseCategoryMapper();
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): CategoriesBackendApiToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(CategoriesBackendApiDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToCategoryImageFacadeInterface
     */
    public function getCategoryImageFacade(): CategoriesBackendApiToCategoryImageFacadeInterface
    {
        return $this->getProvidedDependency(CategoriesBackendApiDependencyProvider::FACADE_CATEGORY_IMAGE);
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Mapper\CategoryMapperInterface
     */
    public function createCategoryMapper(): CategoryMapperInterface
    {
        return new CategoryMapper(
            $this->getLocaleFacade(),
            $this->getStoreFacade(),
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Processor\Reader\CategoryReaderInterface
     */
    public function createCategoryReader(): CategoryReaderInterface
    {
        return new CategoryReader(
            $this->createGlueRequestCategoryMapper(),
            $this->createGlueResponseCategoryMapper(),
            $this->getCategoryFacade(),
            $this->getCategoryImageFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Processor\Deleter\CategoryDeleterInterface
     */
    public function createCategoryDeleter(): CategoryDeleterInterface
    {
        return new CategoryDeleter(
            $this->createGlueRequestCategoryMapper(),
            $this->createGlueResponseCategoryMapper(),
            $this->getCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Processor\Updater\CategoryUpdaterInterface
     */
    public function createCategoryUpdater(): CategoryUpdaterInterface
    {
        return new CategoryUpdater(
            $this->createGlueRequestCategoryMapper(),
            $this->createGlueResponseCategoryMapper(),
            $this->getCategoryFacade(),
            $this->createCategoryMapper(),
            $this->createCategoryReader(),
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): CategoriesBackendApiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(CategoriesBackendApiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Dependency\Facade\CategoriesBackendApiToStoreFacadeInterface
     */
    public function getStoreFacade(): CategoriesBackendApiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(CategoriesBackendApiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Glue\CategoriesBackendApi\Processor\Creator\CategoryCreatorInterface
     */
    public function createCategoryCreator(): CategoryCreatorInterface
    {
        return new CategoryCreator(
            $this->getCategoryFacade(),
            $this->createCategoryMapper(),
            $this->createGlueRequestCategoryMapper(),
            $this->createGlueResponseCategoryMapper(),
        );
    }
}
