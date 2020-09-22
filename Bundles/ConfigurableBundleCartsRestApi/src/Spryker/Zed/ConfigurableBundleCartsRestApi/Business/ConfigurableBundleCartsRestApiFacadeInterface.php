<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi\Business;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;

interface ConfigurableBundleCartsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Expects `quote.customer`, `quote.uuid` to be provided.
     * - Requires `configuredBundle.quantity` property to control amount of configured bundles put to cart.
     * - Requires `configuredBundle.template.uuid` property to populate configurable bundle template related data.
     * - Requires `items` property with `sku`, `quantity` and `configuredBundleItem.slot.uuid` properties to define how many items were added in total to a specific slot.
     * - Adds configured bundle to the cart.
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
     * - Expects `quote.customer` to be provided.
     * - Requires `configuredBundle.quantity` property to control amount of configured bundles put to cart.
     * - Requires `configuredBundle.template.uuid` property to populate configurable bundle template related data.
     * - Requires `items` property with `sku`, `quantity` and `configuredBundleItem.slot.uuid` properties to define how many items were added in total to a specific slot.
     * - If `quote.uuid` is not provided, retrieves all customer's quotes and picks first one.
     * - If customer's quote collection is empty - new quote will be created.
     * - Adds configured bundle to the cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function addConfiguredBundleToGuestCart(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): QuoteResponseTransfer;

    /**
     * Specification:
     * - Expects `quote.customer` and `quote.uuid` to be provided.
     * - Requires `groupKey` property to find all items related to configured bundle.
     * - Requires `quantity` property to control amount of configured bundles put to cart.
     * - Updates configured bundle quantity.
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
     * - Expects `quote.customer` and `quote.uuid` to be provided.
     * - Requires `groupKey` property to find all items related to configured bundle.
     * - Removes configured bundle from cart.
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
