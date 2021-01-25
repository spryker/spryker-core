<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientBridge;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientBridge;

/**
 * @method \Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig getConfig()
 */
class MerchantOpeningHoursRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MERCHANT_OPENING_HOURS_STORAGE = 'CLIENT_MERCHANT_OPENING_HOURS_STORAGE';
    public const CLIENT_MERCHANT_STORAGE = 'CLIENT_MERCHANT_STORAGE';
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addMerchantOpeningHoursStorageClient($container);
        $container = $this->addMerchantStorageClient($container);
        $container = $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addMerchantOpeningHoursStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_OPENING_HOURS_STORAGE, function (Container $container) {
            return new MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientBridge(
                $container->getLocator()->merchantOpeningHoursStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addMerchantStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_STORAGE, function (Container $container) {
            return new MerchantOpeningHoursRestApiToMerchantStorageClientBridge(
                $container->getLocator()->merchantStorage()->client()
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
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new MerchantOpeningHoursRestApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client()
            );
        });

        return $container;
    }
}
