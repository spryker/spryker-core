<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

interface ProductConfigurationClientInterface
{
    /**
     * Specification:
     * - Requires fields to be set on the `ProductConfiguratorResponseProcessorResponseTransfer`:
     *   - `productConfiguratorResponse::checkSum`
     *   - `productConfiguratorResponse::timestamp`
     *   - `productConfiguratorResponse::sourceType`
     *   - `productConfiguratorResponse::productConfigurationInstance::configuratorKey`.
     * - Validates checkSum and timestamp according to provided response data.
     * - Returns `isSuccessful=true` on success or `isSuccessful=false` with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validateProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData` to be set.
     * - Expands `ProductConfiguratorRequestTransfer::productConfiguratorRequestData` with customer, store, locale, currency and price data.
     * - Executes `ProductConfiguratorRequestExpanderPluginInterface` plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    public function expandProductConfiguratorRequestWithContextData(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorRequestTransfer::productConfiguratorRequestData` to be set.
     * - Sends a request to the given product configurator URL with provided data.
     * - If a request is successful than `isSuccessful` flag equals to true.
     * - Returns error messages when request failed and `isSuccessful` flag equals to false.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function sendProductConfiguratorAccessTokenRequest(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer;

    /**
     * Specification:
     * - Requires `ProductConfiguratorResponseTransfer::productConfigurationInstance` to be set.
     * - Maps raw product configurator response data to `ProductConfiguratorResponseTransfer`.
     * - The `ProductConfiguratorResponseTransfer::productConfigurationInstance::prices` is expected to use `PriceProductTransfer` structure.
     * - Executes `ProductConfigurationPriceExtractorPluginInterface` plugins.
     *
     * @api
     *
     * @param array<string, mixed> $configuratorResponseData
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer
     */
    public function mapProductConfiguratorCheckSumResponse(
        array $configuratorResponseData,
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): ProductConfiguratorResponseTransfer;
}
