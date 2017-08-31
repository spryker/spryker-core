<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class GiftCardDependencyProvider extends AbstractBundleDependencyProvider
{

    const SERVICE_ENCODING = 'SERVICE_ENCODING';
    const ATTRIBUTE_PROVIDER_PLUGINS = 'ATTRIBUTE_PROVIDER_PLUGINS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addEncodingService($container);
        $container = $this->addAttributePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEncodingService(Container $container)
    {
        $container[static::SERVICE_ENCODING] = function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAttributePlugins(Container $container)
    {
        $container[static::ATTRIBUTE_PROVIDER_PLUGINS] = function (Container $container) {
            return $this->getAttributeProviderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardAttributePluginInterface[]
     */
    protected function getAttributeProviderPlugins()
    {
        return [];
    }

}
