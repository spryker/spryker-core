<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilder;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Creator\ProductAttributeCreator;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Creator\ProductAttributeCreatorInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpander;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapper;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReader;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Updater\ProductAttributeUpdater;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Updater\ProductAttributeUpdaterInterface;

/**
 * @method \Spryker\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiConfig getConfig()
 */
class ProductAttributesBackendApiFactory extends AbstractBackendApiFactory
{
    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Processor\Creator\ProductAttributeCreatorInterface
     */
    public function createProductAttributeCreator(): ProductAttributeCreatorInterface
    {
        return new ProductAttributeCreator(
            $this->getProductAttributeFacade(),
            $this->createProductAttributeRestResponseBuilder(),
            $this->createProductAttributeMapper(),
            $this->createProductAttributeReader(),
            $this->createProductAttributeExpander(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface
     */
    public function createProductAttributeRestResponseBuilder(): ProductAttributeRestResponseBuilderInterface
    {
        return new ProductAttributeRestResponseBuilder($this->createProductAttributeMapper());
    }

    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface
     */
    public function createProductAttributeMapper(): ProductAttributeMapperInterface
    {
        return new ProductAttributeMapper();
    }

    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface
     */
    public function createProductAttributeExpander(): ProductAttributeExpanderInterface
    {
        return new ProductAttributeExpander($this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Processor\Updater\ProductAttributeUpdaterInterface
     */
    public function createProductAttributeUpdater(): ProductAttributeUpdaterInterface
    {
        return new ProductAttributeUpdater(
            $this->getProductAttributeFacade(),
            $this->createProductAttributeRestResponseBuilder(),
            $this->createProductAttributeMapper(),
            $this->createProductAttributeReader(),
            $this->createProductAttributeExpander(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface
     */
    public function createProductAttributeReader(): ProductAttributeReaderInterface
    {
        return new ProductAttributeReader(
            $this->getProductAttributeFacade(),
            $this->createProductAttributeRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface
     */
    public function getProductAttributeFacade(): ProductAttributesBackendApiToProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributesBackendApiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    /**
     * @return \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductAttributesBackendApiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributesBackendApiDependencyProvider::FACADE_LOCALE);
    }
}
