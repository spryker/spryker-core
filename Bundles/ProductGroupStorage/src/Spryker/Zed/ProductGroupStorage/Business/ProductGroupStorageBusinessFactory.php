<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductGroupStorage\Business\Storage\ProductAbstractGroupStorageWriter;

/**
 * @method \Spryker\Zed\ProductGroupStorage\ProductGroupStorageConfig getConfig()
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 */
class ProductGroupStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductGroupStorage\Business\Storage\ProductAbstractGroupStorageWriterInterface
     */
    public function createProductGroupStorageWriter()
    {
        return new ProductAbstractGroupStorageWriter(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }
}
