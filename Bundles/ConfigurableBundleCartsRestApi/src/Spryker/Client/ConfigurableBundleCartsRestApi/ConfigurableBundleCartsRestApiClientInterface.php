<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCartsRestApi;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;

interface ConfigurableBundleCartsRestApiClientInterface
{
    /**
     * Specification:
     * - Makes Zed request.
     * - Adds configured bundle to the cart.
     * - Expects `quote.customer` to be provided.
     * - Requires `configuredBundle.quantity` property to control amount of configured bundles put to cart.
     * - Requires `configuredBundle.template.uuid` property to populate configurable bundle template related data.
     * - Requires `items` property with `sku`, `quantity` and `configuredBundleItem.slot.uuid` properties to define how many items were added in total to a specific slot.
     * - Returns `QuoteResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundle(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Updates configured bundle quantity.
     * - Expects `quote.customer` and `quote.uuid` to be provided.
     * - Requires `configuredBundle.groupKey` property to find all items related to configured bundle.
     * - Requires `configuredBundle.quantity` property to control amount of configured bundles put to cart.
     * - Returns `QuoteResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(
        UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Removes configured bundle from cart.
     * - Requires `configuredBundle.groupKey` property to find all items related to configured bundle.
     * - Returns `QuoteResponseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(
        UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer;
}
