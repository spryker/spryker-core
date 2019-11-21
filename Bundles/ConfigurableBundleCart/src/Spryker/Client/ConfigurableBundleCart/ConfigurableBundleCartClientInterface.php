<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;

interface ConfigurableBundleCartClientInterface
{
    /**
     * Specification:
     * - Adds configured bundle to the cart.
     * - Requires `configuredBundleRequest` and `items` properties to be set for CreateConfiguredBundleRequestTransfer.
     * - Requires `quantity`, `templateUuid` properties to be set for ConfiguredBundleRequestTransfer.
     * - Requires `sku`, `quantity`, `slotUuid` properties to be set for ConfiguredBundleItemRequestTransfer.
     * - ConfiguredBundleRequestTransfer:quantity is used for configured bundle quantity.
     * - ConfiguredBundleItemRequestTransfer::quantity is used for bundle item quantity.
     * - Returns QuoteResponseTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundle(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Removes configured bundle from cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer;

    /**
     * Specification:
     * - Updates configured bundle quantity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer;
}
