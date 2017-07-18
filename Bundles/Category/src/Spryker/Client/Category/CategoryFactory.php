<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Category;

use Spryker\Client\Category\KeyBuilder\CategoryNodeKeyBuilder;
use Spryker\Client\Category\Storage\CategoryNodeStorage;
use Spryker\Client\Kernel\AbstractFactory;

class CategoryFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Category\Storage\CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage()
    {
        return new CategoryNodeStorage(
            $this->getStorageClient(),
            $this->getCategoryNodeKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\Category\Dependency\Client\CategoryToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function getCategoryNodeKeyBuilder()
    {
        return new CategoryNodeKeyBuilder();
    }

}
