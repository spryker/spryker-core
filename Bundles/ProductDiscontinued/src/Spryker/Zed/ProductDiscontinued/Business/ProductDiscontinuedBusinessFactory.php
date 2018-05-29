<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Business;

use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedDeactivator\ProductDiscontinuedDeactivator;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedDeactivator\ProductDiscontinuedDeactivatorInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedReader\ProductDiscontinuedReader;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedReader\ProductDiscontinuedReaderInterface;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedWriter\ProductDiscontinuedWriter;
use Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedWriter\ProductDiscontinuedWriterInterface;
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
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedWriter\ProductDiscontinuedWriterInterface
     */
    public function createProductDiscontinuedWriter(): ProductDiscontinuedWriterInterface
    {
        return new ProductDiscontinuedWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedReader\ProductDiscontinuedReaderInterface
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
        return new ProductDiscontinuedDeactivator($this->getRepository(), $this->getProductFacade(), $logger);
    }

    /**
     * @return \Spryker\Zed\ProductDiscontinued\Dependency\Facade\ProductDiscontinuedToProductFacadeInterface
     */
    public function getProductFacade(): ProductDiscontinuedToProductFacadeInterface
    {
        return $this->getProvidedDependency(ProductDiscontinuedDependencyProvider::FACADE_PRODUCT);
    }
}
