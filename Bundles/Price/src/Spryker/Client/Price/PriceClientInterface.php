<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price;

/**
 * @method \Spryker\Client\Price\PriceFactory getFactory()
 * @method \Spryker\Client\Price\PriceConfig getConfig()
 */
interface PriceClientInterface
{
    /**
     * Specification:
     *  - Returns current selected price mode as stored in quote
     *  - If its not yet set then uses default price mode as defined in environment configuration
     *
     * @api
     *
     * @return string
     */
    public function getCurrentPriceMode();

    /**
     * Specification:
     * - Checks if price mode is acceptable.
     * - Executes {@link \Spryker\Client\PriceExtension\Dependency\Plugin\CurrentPriceModePreCheckPluginInterface} plugins to check if price mode can be updated.
     * - Sets price mode to quote.
     * - Calls price mode update plugins.
     *
     * @api
     *
     * @param string $priceMode
     *
     * @return void
     */
    public function switchPriceMode(string $priceMode): void;

    /**
     * Specification:
     *  - Return identifier for gross price mode pricing. Same identifier is used when persisting prices
     *
     * @api
     *
     * @return string
     */
    public function getGrossPriceModeIdentifier();

    /**
     * Specification:
     *  - Return identifier for net price mode pricing. Same identifier is used when persisting prices
     *
     * @api
     *
     * @return string
     */
    public function getNetPriceModeIdentifier();

    /**
     * Specification:
     *  - Returns all available price modes
     *
     * @api
     *
     * @return array<string>
     */
    public function getPriceModes();
}
