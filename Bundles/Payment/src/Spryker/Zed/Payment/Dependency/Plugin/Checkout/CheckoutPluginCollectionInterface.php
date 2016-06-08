<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency\Plugin\Checkout;

interface CheckoutPluginCollectionInterface
{

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface $plugin
     * @param string $provider
     * @param string $type
     *
     * @return $this
     */
    public function add(CheckoutPluginInterface $plugin, $provider, $type);

    /**
     * @param $provider
     * @param $pluginType
     *
     * @return void
     */
    public function get($provider, $pluginType);

}
