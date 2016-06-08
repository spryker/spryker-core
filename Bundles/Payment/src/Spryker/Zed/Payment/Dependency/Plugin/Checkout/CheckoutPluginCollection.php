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
        if (!isset($this->plugins[$provider])) {
            $this->plugins[$provider] = [];
        }

        $this->plugins[$provider][$pluginType] = $plugin;

        return $this;
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
        if (empty($this->plugins[$provider])) {
            throw new CheckoutPluginNotFoundException(
                sprintf('Could not find any plugin for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $provider)
            );
        }

        if (empty($this->plugins[$provider][$pluginType])) {
            throw new CheckoutPluginNotFoundException(
                sprintf('Could not find "%s" plugin type for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $pluginType, $provider)
            );
        }

        return $this->plugins[$provider][$pluginType];
    }

}
