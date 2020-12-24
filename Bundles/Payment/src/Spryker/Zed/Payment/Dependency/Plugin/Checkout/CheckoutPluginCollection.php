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
     * @param string $type
     *
     * @return $this
     */
    public function add(CheckoutPluginInterface $plugin, $provider, $type)
    {
        if (!isset($this->plugins[$type])) {
            $this->plugins[$type] = [];
        }

        $this->plugins[$type][$provider] = $plugin;

        return $this;
    }

    /**
     * @param string $provider
     * @param string $type
     *
     * @return bool
     */
    public function has($provider, $type)
    {
        return isset($this->plugins[$type][$provider]);
    }

    /**
     * @param string $provider
     * @param string $type
     *
     * @throws \Spryker\Zed\Payment\Exception\CheckoutPluginNotFoundException
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface
     */
    public function get($provider, $type)
    {
        if (empty($this->plugins[$type])) {
            throw new CheckoutPluginNotFoundException(
                sprintf('Could not find "%s" plugin type for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $type, $provider)
            );
        }

        if (empty($this->plugins[$type][$provider])) {
            throw new CheckoutPluginNotFoundException(
                sprintf('Could not find any plugin for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $provider)
            );
        }

        return $this->plugins[$type][$provider];
    }
}
