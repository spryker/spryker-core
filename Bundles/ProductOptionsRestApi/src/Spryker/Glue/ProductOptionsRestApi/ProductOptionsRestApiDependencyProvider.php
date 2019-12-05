<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToCurrencyClientBridge;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductOptionStorageClientBridge;
use Spryker\Glue\ProductOptionsRestApi\Dependency\Client\ProductOptionsRestApiToProductStorageClientBridge;

/**
 * @method \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig getConfig()
 */
class ProductOptionsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PRODUCT_OPTION_STORAGE_CLIENT = 'PRODUCT_OPTION_STORAGE_CLIENT';
    public const PRODUCT_STORAGE_CLIENT = 'PRODUCT_STORAGE_CLIENT';
    public const GLOSSARY_STORAGE_CLIENT = 'GLOSSARY_STORAGE_CLIENT';
    public const CURRENCY_CLIENT = 'CURRENCY_CLIENT';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addProductOptionStorageClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addCurrencyClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductOptionStorageClient(Container $container): Container
    {
        $container->set(static::PRODUCT_OPTION_STORAGE_CLIENT, function (Container $container) {
            return new ProductOptionsRestApiToProductOptionStorageClientBridge(
                $container->getLocator()->productOptionStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::PRODUCT_STORAGE_CLIENT, function (Container $container) {
            return new ProductOptionsRestApiToProductStorageClientBridge(
                $container->getLocator()->productStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::GLOSSARY_STORAGE_CLIENT, function (Container $container) {
            return new ProductOptionsRestApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container->set(static::CURRENCY_CLIENT, function (Container $container) {
            return new ProductOptionsRestApiToCurrencyClientBridge(
                $container->getLocator()->currency()->client()
            );
        });

        return $container;
    }
}
