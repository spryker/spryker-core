<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency\Plugin\Sales;

use Spryker\Zed\Payment\Exception\PaymentHydratorPluginNotFoundException;

class PaymentHydratorPluginCollection implements PaymentHydratorPluginCollectionInterface
{

    /**
     * @var \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface[]
     */
    protected $plugins = [];

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface $plugin
     * @param string $provider
     *
     * @return $this
     */
    public function add(PaymentHydratorPluginInterface $plugin, $provider)
    {
        $this->plugins[$provider] = $plugin;

        return $this;
    }

    /**
     * @param string $provider
     *
     * @return bool
     */
    public function has($provider)
    {
        return isset($this->plugins[$provider]);
    }

    /**
     * @param string $provider
     *
     * @throws \Spryker\Zed\Payment\Exception\PaymentHydratorPluginNotFoundException
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface
     */
    public function get($provider)
    {
        if (empty($this->plugins[$provider])) {
            throw new PaymentHydratorPluginNotFoundException(
                sprintf('Could not find plugin for "%s" provider. You need to add the needed plugins within your DependencyInjector.', $provider)
            );
        }

        return $this->plugins[$provider];
    }

}
