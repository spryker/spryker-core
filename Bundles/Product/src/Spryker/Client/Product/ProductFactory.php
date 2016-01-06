<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product;

use Spryker\Client\Product\KeyBuilder\ProductResourceKeyBuilder;
use Spryker\Client\Product\Storage\ProductStorage;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Product\Storage\ProductStorageInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use Spryker\Client\Locale\LocaleClient;

class ProductFactory extends AbstractFactory
{

    /**
     * @param string $locale
     *
     * @return ProductStorageInterface
     */
    public function createProductStorage($locale)
    {
        return new ProductStorage(
            $this->getStorage(),
            $this->getKeyBuilder(),
            $locale
        );
    }

    /**
     * @return StorageClientInterface
     */
    private function getStorage()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::KV_STORAGE);
    }

    /**
     * @return KeyBuilderInterface
     */
    private function getKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

    /**
     * @return LocaleClient
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::CLIENT_LOCALE);
    }

}
