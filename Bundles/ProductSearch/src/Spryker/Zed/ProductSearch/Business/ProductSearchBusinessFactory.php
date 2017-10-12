<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder;
use Spryker\Shared\ProductSearch\Code\KeyBuilder\ProductSearchConfigExtensionKeyBuilder;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeMapWriter;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeReader;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeWriter;
use Spryker\Zed\ProductSearch\Business\Collector\Storage\ProductSearchConfigExtensionCollector;
use Spryker\Zed\ProductSearch\Business\Map\Collector\ProductSearchAttributeCollector;
use Spryker\Zed\ProductSearch\Business\Map\Collector\ProductSearchAttributeMapCollector;
use Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapper;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchAttributeMapMarker;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchAttributeMarker;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchConfigExtensionMarker;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarker;
use Spryker\Zed\ProductSearch\Business\Model\ProductAbstractSearchReader;
use Spryker\Zed\ProductSearch\Business\Model\ProductConcreteSearchReader;
use Spryker\Zed\ProductSearch\Business\Model\ProductSearchWriter;
use Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferMapper;
use Spryker\Zed\ProductSearch\Persistence\Collector\Propel\ProductSearchConfigExtensionCollectorQuery;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface getQueryContainer()
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
        return new ProductSearchMarker($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\ProductSearchAttributeMapperInterface
     */
    public function createProductSearchAttributeMapper()
    {
        return new ProductSearchAttributeMapper($this->getAttributeMapCollectors());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\Collector\ProductSearchAttributeMapCollectorInterface[]
     */
    protected function getAttributeMapCollectors()
    {
        return [
            $this->createProductSearchAttributeMapCollector(),
            $this->createProductSearchAttributeCollector(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\Collector\ProductSearchAttributeMapCollectorInterface
     */
    protected function createProductSearchAttributeMapCollector()
    {
        return new ProductSearchAttributeMapCollector($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\Collector\ProductSearchAttributeMapCollectorInterface
     */
    protected function createProductSearchAttributeCollector()
    {
        return new ProductSearchAttributeCollector(
            $this->createAttributeReader(),
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
     * @return \Spryker\Zed\ProductSearch\Business\Attribute\AttributeReaderInterface
     */
    public function createAttributeReader()
    {
        return new AttributeReader(
            $this->getQueryContainer(),
            $this->getLocaleFacade(),
            $this->createProductAttributeTransferMapper()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Transfer\ProductAttributeTransferMapperInterface
     */
    protected function createProductAttributeTransferMapper()
    {
        return new ProductAttributeTransferMapper(
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

    /**
     * @return \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface
     */
    public function createProductSearchConfigExtensionCollector()
    {
        $productSearchConfigCollector = new ProductSearchConfigExtensionCollector(
            $this->createAttributeReader(),
            $this->getConfig(),
            $this->createProductSearchConfigExtensionKeyBuilder(),
            $this->getUtilDataReaderService()
        );

        $productSearchConfigCollector->setTouchQueryContainer(
            $this->getTouchQueryContainer()
        );
        $productSearchConfigCollector->setQueryBuilder(
            $this->createProductSearchConfigPropelQuery()
        );

        return $productSearchConfigCollector;
    }

    /**
     * @return \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected function getUtilDataReaderService()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::SERVICE_DATA);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductSearchConfigExtensionKeyBuilder()
    {
        return new ProductSearchConfigExtensionKeyBuilder();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToCollectorInterface
     */
    public function getCollectorFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_COLLECTOR);
    }

    /**
     * @return \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected function getTouchQueryContainer()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::QUERY_CONTAINER_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Collector\Persistence\Collector\AbstractCollectorQuery
     */
    protected function createProductSearchConfigPropelQuery()
    {
        return new ProductSearchConfigExtensionCollectorQuery();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Marker\ProductSearchConfigExtensionMarkerInterface
     */
    public function createProductSearchConfigExtensionMarker()
    {
        return new ProductSearchConfigExtensionMarker($this->getTouchFacade());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Model\ProductAbstractSearchReaderInterface
     */
    public function createProductAbstractSearchReader()
    {
        return new ProductAbstractSearchReader(
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Model\ProductConcreteSearchReaderInterface
     */
    public function createProductConcreteSearchReader()
    {
        return new ProductConcreteSearchReader(
            $this->getQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Model\ProductSearchWriterInterface
     */
    public function createProductSearchWriter()
    {
        return new ProductSearchWriter(
            $this->createProductSearchMarker(),
            $this->getQueryContainer()
        );
    }
}
