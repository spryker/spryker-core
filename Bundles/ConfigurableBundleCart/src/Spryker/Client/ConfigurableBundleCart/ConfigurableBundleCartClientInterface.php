<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ConfigurableBundleCartClientInterface
{
    /**
     * Specification:
     * -
     *
     * @api
     *
     * @param string $configuredBundleGroupKey
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(string $configuredBundleGroupKey): QuoteResponseTransfer;

    /**
     * Specification:
     * -
     *
     * @api
     *
     * @param string $configuredBundleGroupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(string $configuredBundleGroupKey, int $quantity): QuoteResponseTransfer;
}
