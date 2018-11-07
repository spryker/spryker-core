<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business;

use Spryker\Zed\CategoryImageStorage\Business\Storage\CategoryImageStorageWriter;
use Spryker\Zed\CategoryImageStorage\Business\Storage\CategoryImageStorageWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageEntityManagerInterface getEntityManager()()
 */
class CategoryImageStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryImageStorage\Business\Storage\CategoryImageStorageWriterInterface
     */
    public function createCategoryImageStorageWriter(): CategoryImageStorageWriterInterface
    {
        return new CategoryImageStorageWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }
}
