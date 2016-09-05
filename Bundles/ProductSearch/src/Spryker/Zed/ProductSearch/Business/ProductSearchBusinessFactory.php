<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeMapWriter;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeReader;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeWriter;
use Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeCollector;
use Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapCollector;
use Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapper;
use Spryker\Zed\ProductSearch\Business\Map\ProductSearchConfigCacheSaver;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchAttributeMapMarker;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchAttributeMarker;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarker;
use Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferGenerator;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer getQueryContainer()
 */
class ProductSearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarkerInterface
     */
    public function createProductSearchMarker()
    {
        return new ProductSearchMarker(
            $this->getTouchFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapperInterface
     */
    public function createProductSearchAttributeMapper()
    {
        return new ProductSearchAttributeMapper($this->getAttributeMapCollectors());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapCollectorInterface[]
     */
    protected function getAttributeMapCollectors()
    {
        return [
            $this->createProductSearchAttributeMapCollector(),
            $this->createProductSearchAttributeCollector(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapCollectorInterface
     */
    protected function createProductSearchAttributeMapCollector()
    {
        return new ProductSearchAttributeMapCollector($this->getSearchClient()->getSearchConfig());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapCollectorInterface
     */
    protected function createProductSearchAttributeCollector()
    {
        return new ProductSearchAttributeCollector(
            $this->createAttributeReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\ProductSearchConfigCacheSaverInterface
     */
    public function createProductSearchConfigCacheSaver()
    {
        return new ProductSearchConfigCacheSaver(
            $this->createAttributeReader(),
            $this->getSearchFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Attribute\AttributeMapWriterInterface
     */
    public function createAttributeMapWriter()
    {
        return new AttributeMapWriter(
            $this->getQueryContainer(),
            $this->getProductFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Attribute\AttributeWriterInterface
     */
    public function createAttributeWriter()
    {
        return new AttributeWriter(
            $this->getQueryContainer(),
            $this->getProductFacade(),
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->createFilterGlossaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToProductInterface
     */
    public function getProductFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface
     */
    public function getGlossaryFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_SEARCH);
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface
     */
    public function createAttributeReader()
    {
        return new AttributeReader(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createProductAttributeTransferGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferGeneratorInterface
     */
    protected function createProductAttributeTransferGenerator()
    {
        return new ProductAttributeTransferGenerator(
            $this->getLocaleFacade(),
            $this->getGlossaryFacade(),
            $this->createFilterGlossaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected function createFilterGlossaryKeyBuilder()
    {
        return new FilterGlossaryKeyBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Marker\AttributeMarkerInterface
     */
    public function createProductSearchAttributeMarker()
    {
        return new ProductSearchAttributeMarker(
            $this->getTouchFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Marker\AttributeMarkerInterface
     */
    public function createProductSearchAttributeMapMarker()
    {
        return new ProductSearchAttributeMapMarker(
            $this->getTouchFacade(),
            $this->getQueryContainer()
        );
    }

}
