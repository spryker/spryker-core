<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractFactory;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToCategoryFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductImageFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToStoreFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToTaxFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToUrlFacadeInterface;
use Spryker\Glue\ProductsBackendApi\Dependency\Service\ProductsBackedApiToUtilEncodingServiceInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Creator\ProductAbstractCreator;
use Spryker\Glue\ProductsBackendApi\Processor\Creator\ProductAbstractCreatorInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Expander\PickingListItemsBackendResourceRelationshipExpander;
use Spryker\Glue\ProductsBackendApi\Processor\Expander\PickingListItemsBackendResourceRelationshipExpanderInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Filter\PickingListItemResourceFilter;
use Spryker\Glue\ProductsBackendApi\Processor\Filter\PickingListItemResourceFilterInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductAbstractMapper;
use Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductAbstractMapperInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductConcreteResourceMapper;
use Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductConcreteResourceMapperInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ConcreteProductResourceRelationshipReader;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ConcreteProductResourceRelationshipReaderInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductAbstractReader;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductAbstractReaderInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductConcreteResourceReader;
use Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductConcreteResourceReaderInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\CategoryUpdater;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\CategoryUpdaterInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\ProductAbstractUpdater;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\ProductAbstractUpdaterInterface;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\UrlUpdater;
use Spryker\Glue\ProductsBackendApi\Processor\Updater\UrlUpdaterInterface;

class ProductsBackendApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductAbstractReaderInterface
     */
    public function createProductAbstractReader(): ProductAbstractReaderInterface
    {
        return new ProductAbstractReader(
            $this->getProductFacade(),
            $this->getUtilEncodingService(),
            $this->getProductCategoryFacade(),
            $this->getProductImageFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Creator\ProductAbstractCreatorInterface
     */
    public function createProductAbstractCreator(): ProductAbstractCreatorInterface
    {
        return new ProductAbstractCreator(
            $this->getProductFacade(),
            $this->createProductAbstractReader(),
            $this->createCategoryUpdater(),
            $this->createProductAbstractMapper(),
            $this->createUrlUpdater(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Updater\ProductAbstractUpdaterInterface
     */
    public function createProductAbstractUpdater(): ProductAbstractUpdaterInterface
    {
        return new ProductAbstractUpdater(
            $this->getProductFacade(),
            $this->createProductAbstractReader(),
            $this->createCategoryUpdater(),
            $this->createProductAbstractMapper(),
            $this->createUrlUpdater(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductAbstractMapperInterface
     */
    public function createProductAbstractMapper(): ProductAbstractMapperInterface
    {
        return new ProductAbstractMapper(
            $this->getLocaleFacade(),
            $this->getTaxFacade(),
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Updater\CategoryUpdaterInterface
     */
    public function createCategoryUpdater(): CategoryUpdaterInterface
    {
        return new CategoryUpdater(
            $this->getCategoryFacade(),
            $this->getProductCategoryFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Updater\UrlUpdaterInterface
     */
    public function createUrlUpdater(): UrlUpdaterInterface
    {
        return new UrlUpdater(
            $this->getUrlFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Expander\PickingListItemsBackendResourceRelationshipExpanderInterface
     */
    public function createPickingListItemsBackendResourceRelationshipExpander(): PickingListItemsBackendResourceRelationshipExpanderInterface
    {
        return new PickingListItemsBackendResourceRelationshipExpander(
            $this->createPickingListItemResourceFilter(),
            $this->createConcreteProductResourceRelationshipReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Reader\ConcreteProductResourceRelationshipReaderInterface
     */
    public function createConcreteProductResourceRelationshipReader(): ConcreteProductResourceRelationshipReaderInterface
    {
        return new ConcreteProductResourceRelationshipReader(
            $this->createProductConcreteResourceReader(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Reader\ProductConcreteResourceReaderInterface
     */
    public function createProductConcreteResourceReader(): ProductConcreteResourceReaderInterface
    {
        return new ProductConcreteResourceReader(
            $this->getProductFacade(),
            $this->createProductConcreteResourceMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Filter\PickingListItemResourceFilterInterface
     */
    public function createPickingListItemResourceFilter(): PickingListItemResourceFilterInterface
    {
        return new PickingListItemResourceFilter();
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Processor\Mapper\ProductConcreteResourceMapperInterface
     */
    public function createProductConcreteResourceMapper(): ProductConcreteResourceMapperInterface
    {
        return new ProductConcreteResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductFacadeInterface
     */
    public function getProductFacade(): ProductsBackendApiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Service\ProductsBackedApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductsBackedApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToTaxFacadeInterface
     */
    public function getTaxFacade(): ProductsBackendApiToTaxFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_TAX);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductsBackendApiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductsBackendApiToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToCategoryFacadeInterface
     */
    public function getCategoryFacade(): ProductsBackendApiToCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductCategoryFacadeInterface
     */
    public function getProductCategoryFacade(): ProductsBackendApiToProductCategoryFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductsBackendApiToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Glue\ProductsBackendApi\Dependency\Facade\ProductsBackendApiToUrlFacadeInterface
     */
    public function getUrlFacade(): ProductsBackendApiToUrlFacadeInterface
    {
        return $this->getProvidedDependency(ProductsBackendApiDependencyProvider::FACADE_URL);
    }
}
