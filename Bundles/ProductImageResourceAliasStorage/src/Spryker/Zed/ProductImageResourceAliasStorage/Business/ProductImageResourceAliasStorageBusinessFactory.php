<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage\ProductAbstractImageStorageWriter;
use Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage\ProductAbstractImageStorageWriterInterface;
use Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage\ProductConcreteImageStorageWriter;
use Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage\ProductConcreteImageStorageWriterInterface;

/**
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Persistence\ProductImageResourceAliasStorageRepositoryInterface getRepository()
 */
class ProductImageResourceAliasStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage\ProductAbstractImageStorageWriterInterface
     */
    public function createProductAbstractImageStorageWriter(): ProductAbstractImageStorageWriterInterface
    {
        return new ProductAbstractImageStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageStorage\ProductConcreteImageStorageWriterInterface
     */
    public function createProductConcreteImageStorageWriter(): ProductConcreteImageStorageWriterInterface
    {
        return new ProductConcreteImageStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
