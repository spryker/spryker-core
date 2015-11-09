<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Product\Service;

use Generated\Client\Ide\FactoryAutoCompletion\ProductService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Product\ProductDependencyProvider;
use SprykerFeature\Client\Product\Service\Storage\ProductStorageInterface;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use SprykerEngine\Client\Locale\Service\LocaleClient;

/**
 * @method ProductService getFactory()
 */
class ProductDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @param string $locale
     *
     * @return ProductStorageInterface
     */
    public function createProductStorage($locale)
    {
        return $this->getFactory()->createStorageProductStorage(
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
        return $this->getFactory()->createKeyBuilderProductResourceKeyBuilder();
    }

    /**
     * @return LocaleClient
     */
    public function getLocaleClient()
    {
        return $this->getProvidedDependency(ProductDependencyProvider::CLIENT_LOCALE);
    }

}
