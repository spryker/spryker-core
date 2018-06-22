<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinued\Business\CartChangePreCheck\CartChangePreCheck;
use Spryker\Zed\ProductDiscontinued\Business\CartChangePreCheck\CartChangePreCheckInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutor;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutorInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedReader;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedReaderInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedWriter;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedWriterInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedDeactivator\ProductDiscontinuedDeactivator;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedDeactivator\ProductDiscontinuedDeactivatorInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedNote\ProductDiscontinuedNoteWriter;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedNote\ProductDiscontinuedNoteWriterInterface;
use Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface;
use Spryker\Zed\ProductDiscontinued\ProductDiscontinuedDependencyProvider;

/**
 * @method \Spryker\Zed\ProductDiscontinued\ProductDiscontinuedConfig getConfig()
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedRepositoryInterface getRepository()
 */
class ProductDiscontinuedBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedWriterInterface
     */
    public function createProductDiscontinuedWriter(): ProductDiscontinuedWriterInterface
    {
        return new ProductDiscontinuedWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createProductDiscontinuedPluginExecutor(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedPluginExecutorInterface
     */
    public function createProductDiscontinuedPluginExecutor(): ProductDiscontinuedPluginExecutorInterface
    {
        return new ProductDiscontinuedPluginExecutor(
            $this->getPostProductDiscontinuePlugins(),
            $this->getPostDeleteProductDiscontinuedPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinued\ProductDiscontinuedReaderInterface
     */
    public function createProductDiscontinuedReader(): ProductDiscontinuedReaderInterface
    {
        return new ProductDiscontinuedReader($this->getRepository());
    }

    /**
     * @param null|\Psr\Log\LoggerInterface $logger
     *
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedDeactivator\ProductDiscontinuedDeactivatorInterface
     */
    public function createProductDiscontinuedDeactivator(?LoggerInterface $logger = null): ProductDiscontinuedDeactivatorInterface
    {
        return new ProductDiscontinuedDeactivator(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getProductFacade(),
            $logger
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\CartChangePreCheck\CartChangePreCheckInterface
     */
    public function createCartChangePreCheck(): CartChangePreCheckInterface
    {
        return new CartChangePreCheck($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedNote\ProductDiscontinuedNoteWriterInterface
     */
    public function createProductDiscontinuedNoteWriter(): ProductDiscontinuedNoteWriterInterface
    {
        return new ProductDiscontinuedNoteWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface
     */
    public function getProductFacade(): ProductDiscontinuedToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface[]
     */
    protected function getPostProductDiscontinuePlugins(): array
    {
        return $this->getProvidedDependency(ProductDiscontinuedDependencyProvider::PLUGINS_POST_PRODUCT_DISCONTINUE);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostDeleteProductDiscontinuedPluginInterface[]
     */
    protected function getPostDeleteProductDiscontinuedPlugins(): array
    {
        return $this->getProvidedDependency(ProductDiscontinuedDependencyProvider::PLUGINS_POST_DELETE_PRODUCT_DISCONTINUED);
    }
}
