<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency\Plugin\Checkout;

use Spryker\Zed\Payment\Exception\CheckoutPluginNotFoundException;

class CheckoutPluginCollection implements CheckoutPluginCollectionInterface
{
    /**
     * @var array
     */
    protected $plugins = [];

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface $plugin
     * @param string $provider
     * @param string $pluginType
     *
     * @return $this
     */
    public function add(CheckoutPluginInterface $plugin, $provider, $pluginType)
    {
        if (!isset($this->plugins[$pluginType])) {
            $this->plugins[$pluginType] = [];
        }

        $this->plugins[$pluginType][$provider] = $plugin;

        return $this;
    }

    /**
     * @param string $provider
     * @param string $pluginType
     *
     * @return bool
     */
    public function has($provider, $pluginType)
    {
        return isset($this->plugins[$pluginType][$provider]);
    }

    /**
     * @param string $provider
     * @param string $pluginType
     *
     * @throws \Spryker\Zed\Payment\Exception\CheckoutPluginNotFoundException
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface
     */
    public function get($provider, $pluginType)
    {
        if (empty($this->plugins[$pluginType])) {
            throw new CheckoutPluginNotFoundException(
                sprintf('Could not find "%s" plugin type for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $pluginType, $provider)
            );
        }

        if (empty($this->plugins[$pluginType][$provider])) {
            throw new CheckoutPluginNotFoundException(
                sprintf('Could not find any plugin for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $provider)
            );
        }

        return $this->plugins[$pluginType][$provider];
    }
}
