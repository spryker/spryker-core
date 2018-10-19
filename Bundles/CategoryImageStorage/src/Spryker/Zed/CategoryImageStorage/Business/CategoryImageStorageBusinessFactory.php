<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business;

use Spryker\Zed\CategoryImageStorage\Business\Storage\CategoryImageStorageWriter;
use Spryker\Zed\CategoryImageStorage\Business\Storage\CategoryImageStorageWriterInterface;
use Spryker\Zed\CategoryImageStorage\CategoryImageStorageDependencyProvider;
use Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryImageStorage\Persistence\CategoryImageStorageRepositoryInterface getRepository()
 */
class CategoryImageStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CategoryImageStorage\Dependency\Facade\CategoryImageStorageToCategoryImageInterface
     */
    public function getCategoryImageFacade(): CategoryImageStorageToCategoryImageInterface
    {
        return $this->getProvidedDependency(CategoryImageStorageDependencyProvider::FACADE_CATEGORY_IMAGE);
    }

    /**
     * @return \Spryker\Zed\CategoryImageStorage\Business\Storage\CategoryImageStorageWriterInterface
     */
    public function createCategoryImageStorageWriter(): CategoryImageStorageWriterInterface
    {
        return new CategoryImageStorageWriter(
            $this->getCategoryImageFacade(),
            $this->getRepository(),
            $this->getConfig()->isSendingToQueue()
        );
    }
}
