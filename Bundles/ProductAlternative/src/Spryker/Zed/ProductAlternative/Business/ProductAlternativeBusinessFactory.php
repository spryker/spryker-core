<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydrator;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydratorInterface;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListManager;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListManagerInterface;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorter;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorterInterface;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReader;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeWriter;
use Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeWriterInterface;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerInterface;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductQueryContainerInterface;
use Spryker\Zed\ProductAlternative\ProductAlternativeDependencyProvider;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface getRepository()
 */
class ProductAlternativeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeWriterInterface
     */
    public function createProductAlternativeWriter(): ProductAlternativeWriterInterface
    {
        return new ProductAlternativeWriter(
            $this->getEntityManager(),
            $this->createProductAlternativeReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface
     */
    public function createProductAlternativeReader(): ProductAlternativeReaderInterface
    {
        return new ProductAlternativeReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydratorInterface
     */
    public function createProductAlternativeListHydrator(): ProductAlternativeListHydratorInterface
    {
        return new ProductAlternativeListHydrator(
            $this->getProductQueryContainer(),
            $this->getProductCategoryQueryContainer(),
            $this->getLocaleFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorterInterface
     */
    public function createProductAlternativeListSorter(): ProductAlternativeListSorterInterface
    {
        return new ProductAlternativeListSorter();
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListManagerInterface
     */
    public function createProductAlternativeListManager(): ProductAlternativeListManagerInterface
    {
        return new ProductAlternativeListManager(
            $this->createProductAlternativeListHydrator(),
            $this->createProductAlternativeReader(),
            $this->createProductAlternativeListSorter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductQueryContainerInterface
     */
    public function getProductQueryContainer(): ProductAlternativeToProductQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAlternativeDependencyProvider::QUERY_CONTAINER_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerInterface
     */
    public function getProductCategoryQueryContainer(): ProductAlternativeToProductCategoryQueryContainerInterface
    {
        return $this->getProvidedDependency(ProductAlternativeDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface
     */
    public function getLocaleFacade(): ProductAlternativeToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductAlternativeDependencyProvider::FACADE_LOCALE);
    }
}
