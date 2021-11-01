<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin;

/**
 * Allows to expand view data for product offer list action.
 */
interface ProductOfferListActionViewDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands view data for list of product offers with new data.
     *
     * @api
     *
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    public function expand(array $viewData): array;
}
