<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeReader;
use Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapCollector;
use Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapper;
use Spryker\Zed\ProductSearch\Business\Marker\ProductSearchMarker;
use Spryker\Zed\ProductSearch\Business\Attribute\AttributeWriter;
use Spryker\Zed\ProductSearch\Business\Saver\SearchPreferencesSaver;
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
     * @return \Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapperInterface
     */
    public function createSearchProductAttributeMapper()
    {
        return new SearchProductAttributeMapper($this->createSearchProductAttributeMapCollector());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapCollectorInterface
     */
    public function createSearchProductAttributeMapCollector()
    {
        return new SearchProductAttributeMapCollector($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Saver\SearchPreferencesSaverInterface
     */
    public function createSearchPreferencesSaver()
    {
        return new SearchPreferencesSaver(
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
            $this->getGlossaryFacade()
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
            $this->getGlossaryFacade()
        );
    }

}
