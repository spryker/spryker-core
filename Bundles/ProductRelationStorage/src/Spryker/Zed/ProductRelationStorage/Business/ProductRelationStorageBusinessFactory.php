<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductRelationStorage\Business\Storage\ProductRelationStorageWriter;

/**
 * @method \Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductRelationStorage\Persistence\ProductRelationStorageRepositoryInterface getRepository()
 */
class ProductRelationStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductRelationStorage\Business\Storage\ProductRelationStorageWriterInterface
     */
    public function createProductRelationStorageWriter()
    {
        return new ProductRelationStorageWriter(
            $this->getQueryContainer(),
            $this->getRepository(),
            $this->getConfig()->isSendingToQueue()
        );
    }
}
