<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductConfigurationCartClientInterface
{
    /**
     * Specification:
     * - Requires `CartChangeTransfer::items::sku` to be set.
     * - Checks if the item has a configuration, if it does, the default configuration will not be set.
     * - Expands the provided cart change transfer items with the corresponding product configuration instance.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithProductConfigurationInstance(
        CartChangeTransfer $cartChangeTransfer,
        array $params
    ): CartChangeTransfer;

    /**
     * Specification:
     * - Returns false if any item with product configuration is not fully configured, true otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteProductConfigurationValid(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Requires `ProductConfiguratorResponseProcessorResponseTransfer::productConfiguratorResponse` to be set.
     * - Requires `ProductConfiguratorResponseProcessorResponseTransfer::productConfiguratorResponse::sku` to be set.
     * - Requires `ProductConfiguratorResponseProcessorResponseTransfer::productConfiguratorResponse::groupKey` to be set.
     * - Maps raw product configurator checksum response.
     * - Validates product configurator checksum response.
     * - Gets current customer quote.
     * - Finds item in the quote.
     * - Handles quantity changes, adds corresponding messages to response.
     * - Replaces item in a quote.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `true` when response was processed.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::isSuccessful` equal to `false` when something went wrong.
     * - Returns `ProductConfiguratorResponseProcessorResponseTransfer::messages` containing error messages, if any was added.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::sku` to be set.
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData::itemGroupKey` to be set.
     * - Finds configuration instance in the quote.
     * - Maps configuration instance to `ProductConfiguratorRequestTransfer`.
     * - Sends product configurator access token request.
     * - Returns `ProductConfiguratorRedirectTransfer::isSuccessful` equal to `true` when redirect URL was successfully resolved.
     * - Returns `ProductConfiguratorRedirectTransfer::isSuccessful` equal to `false` otherwise.
     * - Returns `ProductConfiguratorRedirectTransfer::messages` with errors if any exist.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer;
}
