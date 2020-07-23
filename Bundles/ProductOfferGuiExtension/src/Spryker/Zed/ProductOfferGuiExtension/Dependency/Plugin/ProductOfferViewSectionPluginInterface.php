<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferTransfer;

interface ProductOfferViewSectionPluginInterface
{
    /**
     * Specification:
     * - Returns template for render.
     *
     * @api
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Specification:
     * - Returns data for render in view section of product offer.
     *
     * @api
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getData(ProductOfferTransfer $productOfferTransfer): array;
}
