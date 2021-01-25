<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Dependency\Plugin\Sales;

interface PaymentHydratorPluginCollectionInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface $plugin
     * @param string $provider
     *
     * @return $this
     */
    public function add(PaymentHydratorPluginInterface $plugin, $provider);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $provider
     *
     * @return bool
     */
    public function has($provider);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $provider
     *
     * @throws \Spryker\Zed\Payment\Exception\PaymentHydratorPluginNotFoundException
     *
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Sales\PaymentHydratorPluginInterface
     */
    public function get($provider);
}
