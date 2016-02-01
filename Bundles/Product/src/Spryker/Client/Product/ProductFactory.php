<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Product;

use Spryker\Client\Product\KeyBuilder\ProductResourceKeyBuilder;
use Spryker\Client\Product\Storage\ProductStorage;
use Spryker\Client\Kernel\AbstractFactory;

class ProductFactory extends AbstractFactory
{

    /**
     * @param string $locale
     *
     * @return \Spryker\Client\Product\Storage\ProductStorageInterface
     */
    public function createProductStorage($locale)
    {
        return new ProductStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $locale
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

    /**
     * @return \Spryker\Client\Locale\LocaleClient
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::CLIENT_LOCALE);
    }

}
