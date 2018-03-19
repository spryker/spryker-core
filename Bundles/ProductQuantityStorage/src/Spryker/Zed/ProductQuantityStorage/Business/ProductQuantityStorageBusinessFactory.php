<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductQuantityStorage\Business\Model\ProductQuantityStorageWriter;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\ProductQuantityStorageConfig getConfig()
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface getRepository()
 */
class ProductQuantityStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductQuantityStorage\Business\Model\ProductQuantityStorageWriterInterface
     */
    public function createProductQuantityStorageWriter()
    {
        return new ProductQuantityStorageWriter();
    }
}
