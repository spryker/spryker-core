<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReader;
use Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface;
use Spryker\Zed\ProductList\Business\ProductList\ProductListWriter;
use Spryker\Zed\ProductList\Business\ProductList\ProductListWriterInterface;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationPostSaver;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReader;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriter;
use Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriterInterface;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationPostSaver;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReader;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriter;
use Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriterInterface;

/**
 * @method \Spryker\Zed\ProductList\ProductListConfig getConfig()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductList\Persistence\ProductListEntityManagerInterface getEntityManager()
 */
class ProductListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListReaderInterface
     */
    public function createProductListReader(): ProductListReaderInterface
    {
        return new ProductListReader(
            $this->getRepository(),
            $this->createProductListCategoryRelationReader(),
            $this->createProductListProductConcreteRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListWriterInterface
     */
    public function createProductListWriter(): ProductListWriterInterface
    {
        return new ProductListWriter(
            $this->getEntityManager(),
            $this->getProductListPostSaverCollection()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface
     */
    public function createProductListCategoryRelationPostSaver(): ProductListPostSaverInterface
    {
        return new ProductListCategoryRelationPostSaver($this->createProductListCategoryRelationWriter());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface
     */
    public function createProductListProductConcreteRelationPostSaver(): ProductListPostSaverInterface
    {
        return new ProductListProductConcreteRelationPostSaver($this->createProductListProductConcreteRelationWriter());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationReaderInterface
     */
    public function createProductListCategoryRelationReader(): ProductListCategoryRelationReaderInterface
    {
        return new ProductListCategoryRelationReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationReaderInterface
     */
    public function createProductListProductConcreteRelationReader(): ProductListProductConcreteRelationReaderInterface
    {
        return new ProductListProductConcreteRelationReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListCategoryRelation\ProductListCategoryRelationWriterInterface
     */
    public function createProductListCategoryRelationWriter(): ProductListCategoryRelationWriterInterface
    {
        return new ProductListCategoryRelationWriter(
            $this->getEntityManager(),
            $this->createProductListCategoryRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductListProductConcreteRelation\ProductListProductConcreteRelationWriterInterface
     */
    public function createProductListProductConcreteRelationWriter(): ProductListProductConcreteRelationWriterInterface
    {
        return new ProductListProductConcreteRelationWriter(
            $this->getEntityManager(),
            $this->createProductListProductConcreteRelationReader()
        );
    }

    /**
     * @return \Spryker\Zed\ProductList\Business\ProductList\ProductListPostSaverInterface[]
     */
    public function getProductListPostSaverCollection(): array
    {
        return [
            $this->createProductListCategoryRelationPostSaver(),
            $this->createProductListProductConcreteRelationPostSaver(),
        ];
    }
}
