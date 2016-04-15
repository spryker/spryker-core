<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryExporter;

use Spryker\Client\Cart\CartDependencyProvider;
use Spryker\Client\CategoryExporter\Builder\CategoryTreeBuilder;
use Spryker\Client\CategoryExporter\KeyBuilder\CategoryResourceKeyBuilder;
use Spryker\Client\CategoryExporter\KeyBuilder\NavigationKeyBuilder as KeyBuilderNavigationKeyBuilder;
use Spryker\Client\CategoryExporter\Model\Navigation;
use Spryker\Client\Kernel\AbstractFactory;

class CategoryExporterFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\CategoryExporter\Model\Navigation
     */
    public function createNavigation()
    {
        return new Navigation(
            $this->getStorageClient(),
            $this->createNavigationKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\CategoryExporter\Builder\CategoryTreeBuilder
     */
    public function createCategoryTreeBuilder()
    {
        return new CategoryTreeBuilder(
            $this->getStorageClient(),
            $this->createResourceKeyBuilder()
        );
    }

    /**
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder
     */
    protected function createNavigationKeyBuilder()
    {
        return new KeyBuilderNavigationKeyBuilder();
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createResourceKeyBuilder()
    {
        return new CategoryResourceKeyBuilder();
    }

}
