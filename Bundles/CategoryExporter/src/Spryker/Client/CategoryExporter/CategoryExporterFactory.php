<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\CategoryExporter;

use Spryker\Client\Cart\CartDependencyProvider;
use Spryker\Client\CategoryExporter\KeyBuilder\CategoryResourceKeyBuilder;
use Spryker\Client\CategoryExporter\KeyBuilder\NavigationKeyBuilder as KeyBuilderNavigationKeyBuilder;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\CategoryExporter\Builder\CategoryTreeBuilder;
use Spryker\Client\CategoryExporter\Model\Navigation;
use Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\CategoryExporter\Code\KeyBuilder\NavigationKeyBuilder;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class CategoryExporterFactory extends AbstractFactory
{

    /**
     * @return Navigation
     */
    public function createNavigation()
    {
        return new Navigation(
            $this->getStorageClient(),
            $this->createNavigationKeyBuilder()
        );
    }

    /**
     * @return CategoryTreeBuilder
     */
    public function createCategoryTreeBuilder()
    {
        return new CategoryTreeBuilder(
            $this->getStorageClient(),
            $this->createResourceKeyBuilder()
        );
    }

    /**
     * @throws ContainerKeyNotFoundException
     * @return StorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(CartDependencyProvider::CLIENT_KV_STORAGE);
    }

    /**
     * @return NavigationKeyBuilder
     */
    protected function createNavigationKeyBuilder()
    {
        return new KeyBuilderNavigationKeyBuilder();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function createResourceKeyBuilder()
    {
        return new CategoryResourceKeyBuilder();
    }

}
