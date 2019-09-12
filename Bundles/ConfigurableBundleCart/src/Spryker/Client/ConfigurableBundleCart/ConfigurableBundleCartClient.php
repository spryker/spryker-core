<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ConfigurableBundleCart\ConfigurableBundleCartFactory getFactory()
 */
class ConfigurableBundleCartClient extends AbstractClient implements ConfigurableBundleCartClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $configuredBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(string $configuredBundleGroupKey): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartWriter()
            ->removeConfiguredBundle($configuredBundleGroupKey);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $configuredBundleGroupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(string $configuredBundleGroupKey, int $quantity): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartWriter()
            ->updateConfiguredBundleQuantity($configuredBundleGroupKey, $quantity);
    }
}
