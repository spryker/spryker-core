<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetStorage\Business\Storage\ProductSetStorageWriter;

/**
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface getQueryContainer()
 */
class ProductSetStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductSetStorage\Business\Storage\ProductSetStorageWriterInterface
     */
    public function createProductSetStorageWriter()
    {
        return new ProductSetStorageWriter(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue()
        );
    }
}
