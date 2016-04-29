<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSearch\Business\Locator\OperationLocator;
use Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapCollector;
use Spryker\Zed\ProductSearch\Business\Operation\AddToResult;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToFacet;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToField;
use Spryker\Zed\ProductSearch\Business\Operation\CopyToMultiField;
use Spryker\Zed\ProductSearch\Business\Operation\DefaultOperation;
use Spryker\Zed\ProductSearch\Business\Operation\OperationManager;
use Spryker\Zed\ProductSearch\Business\Processor\ProductSearchMarker;
use Spryker\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformer;
use Spryker\Zed\ProductSearch\ProductSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSearch\ProductSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainer getQueryContainer()
 */
class ProductSearchBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Transformer\ProductAttributesTransformerInterface
     */
    public function createProductAttributesTransformer()
    {
        return new ProductAttributesTransformer(
            $this->getQueryContainer(),
            $this->createOperationLocator(),
            $this->createDefaultOperation()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface
     */
    protected function createDefaultOperation()
    {
        return new DefaultOperation();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Locator\OperationLocatorInterface
     */
    protected function createOperationLocator()
    {
        $locator = new OperationLocator();
        $operations = $this->getPossibleOperations();

        foreach ($operations as $operation) {
            $locator->addOperation($operation);
        }

        return $locator;
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\OperationManagerInterface
     */
    protected function createOperationManager()
    {
        return new OperationManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface
     */
    protected function getTouchFacade()
    {
        return $this->getProvidedDependency(ProductSearchDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return Store::getInstance()->getStoreName();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\OperationInterface[]
     */
    protected function getPossibleOperations()
    {
        return [
            $this->createAddToResult(),
            $this->createCopyToField(),
            $this->createCopyToFacet(),
            $this->createCopyToMultiField(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\AddToResult
     */
    protected function createAddToResult()
    {
        return new AddToResult();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\CopyToField
     */
    protected function createCopyToField()
    {
        return new CopyToField();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\CopyToFacet
     */
    protected function createCopyToFacet()
    {
        return new CopyToFacet();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Operation\CopyToMultiField
     */
    protected function createCopyToMultiField()
    {
        return new CopyToMultiField();
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Processor\ProductSearchMarkerInterface
     */
    public function createProductSearchMarker()
    {
        return new ProductSearchMarker(
            $this->getTouchFacade(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\ProductSearch\Business\Map\SearchProductAttributeMapCollectorInterface
     */
    public function createSearchProductAttributeMapCollector()
    {
        return new SearchProductAttributeMapCollector($this->getQueryContainer());
    }

}
