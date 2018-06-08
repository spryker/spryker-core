<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationReader;
use Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationReaderInterface;
use Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationWriter;
use Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationWriterInterface;
use Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationReader;
use Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationReaderInterface;
use Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationWriter;
use Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationWriterInterface;
use Spryker\Zed\ProductList\Business\Model\ProductListWriter;
use Spryker\Zed\ProductList\Business\Model\ProductListWriterInterface;

/**
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface getEntityManager()
 */
class ProductListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductList\Business\Model\ProductListWriterInterface
     */
    public function createProductListWriter(): ProductListWriterInterface
    {
        return new ProductListWriter(
            $this->getEntityManager(),
            $this->createProductListCategoryRelationWriter(),
            $this->createProductListProductConcreteRelationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationReaderInterface
     */
    public function createProductListCategoryRelationReader(): ProductListCategoryRelationReaderInterface
    {
        return new ProductListCategoryRelationReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationReaderInterface
     */
    public function createProductListProductConcreteRelationReader(): ProductListProductConcreteRelationReaderInterface
    {
        return new ProductListProductConcreteRelationReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\Model\ProductListCategoryRelationWriterInterface
     */
    public function createProductListCategoryRelationWriter(): ProductListCategoryRelationWriterInterface
    {
        return new ProductListCategoryRelationWriter(
            $this->getEntityManager(),
            $this->createProductListCategoryRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\Model\ProductListProductConcreteRelationWriterInterface
     */
    public function createProductListProductConcreteRelationWriter(): ProductListProductConcreteRelationWriterInterface
    {
        return new ProductListProductConcreteRelationWriter(
            $this->getEntityManager(),
            $this->createProductListProductConcreteRelationReader()
        );
    }
}
