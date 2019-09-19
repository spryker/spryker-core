<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage\ProductAbstractStorageWriter;
use Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage\ProductAbstractStorageWriterInterface;
use Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage\ProductConcreteStorageWriter;
use Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage\ProductConcreteStorageWriterInterface;

/**
 * @method \Spryker\Zed\ProductResourceAliasStorage\ProductResourceAliasStorageConfig getConfig()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Business\ProductResourceAliasStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Persistence\ProductResourceAliasStorageRepositoryInterface getRepository()
 */
class ProductResourceAliasStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage\ProductAbstractStorageWriterInterface
     */
    public function createProductAbstractStorageWriter(): ProductAbstractStorageWriterInterface
    {
        return new ProductAbstractStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductResourceAliasStorage\Business\ProductStorage\ProductConcreteStorageWriterInterface
     */
    public function createProductConcreteStorageWriter(): ProductConcreteStorageWriterInterface
    {
        return new ProductConcreteStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
