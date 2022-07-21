<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ArrayExpander;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ArrayExpanderInterface;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductAbstractEditViewExpander;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductAbstractEditViewExpanderInterface;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableActionExpander;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableActionExpanderInterface;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableConfigurationExpander;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableConfigurationExpanderInterface;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableDataExpander;
use Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableDataExpanderInterface;
use Spryker\Zed\ProductApprovalGui\Communication\Reader\ProductApprovalStatusReader;
use Spryker\Zed\ProductApprovalGui\Communication\Reader\ProductApprovalStatusReaderInterface;
use Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface;
use Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductFacadeInterface;
use Spryker\Zed\ProductApprovalGui\ProductApprovalGuiDependencyProvider;
use Twig\Environment;

/**
 * @method \Spryker\Zed\ProductApprovalGui\Persistence\ProductApprovalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductApprovalGui\ProductApprovalGuiConfig getConfig()
 */
class ProductApprovalGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableConfigurationExpanderInterface
     */
    public function createProductApprovalProductTableConfigurationExpander(): ProductApprovalProductTableConfigurationExpanderInterface
    {
        return new ProductApprovalProductTableConfigurationExpander($this->createArrayExpander());
    }

    /**
     * @return \Spryker\Zed\ProductApprovalGui\Communication\Expander\ArrayExpanderInterface
     */
    public function createArrayExpander(): ArrayExpanderInterface
    {
        return new ArrayExpander();
    }

    /**
     * @return \Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableActionExpanderInterface
     */
    public function createProductApprovalProductTableActionExpander(): ProductApprovalProductTableActionExpanderInterface
    {
        return new ProductApprovalProductTableActionExpander($this->createProductApprovalStatusReader());
    }

    /**
     * @return \Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductAbstractEditViewExpanderInterface
     */
    public function createProductApprovalProductAbstractEditViewExpander(): ProductApprovalProductAbstractEditViewExpanderInterface
    {
        return new ProductApprovalProductAbstractEditViewExpander(
            $this->createProductApprovalStatusReader(),
            $this->getProductFacade(),
            $this->getTwig(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableDataExpanderInterface
     */
    public function createProductApprovalProductTableDataExpander(): ProductApprovalProductTableDataExpanderInterface
    {
        return new ProductApprovalProductTableDataExpander(
            $this->createArrayExpander(),
            $this->getTwig(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductApprovalGui\Communication\Reader\ProductApprovalStatusReaderInterface
     */
    public function createProductApprovalStatusReader(): ProductApprovalStatusReaderInterface
    {
        return new ProductApprovalStatusReader(
            $this->getConfig(),
            $this->getProductApprovalFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductFacadeInterface
     */
    public function getProductFacade(): ProductApprovalGuiToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductApprovalGuiDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductApprovalGui\Dependency\Facade\ProductApprovalGuiToProductApprovalFacadeInterface
     */
    public function getProductApprovalFacade(): ProductApprovalGuiToProductApprovalFacadeInterface
    {
        return $this->getProvidedDependency(ProductApprovalGuiDependencyProvider::FACADE_PRODUCT_APPROVAL);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwig(): Environment
    {
        return $this->getProvidedDependency(ProductApprovalGuiDependencyProvider::SERVICE_TWIG);
    }
}
