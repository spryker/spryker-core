<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductOfferTransfer;

/**
 * Provides ability to expand view section for product offer.
 */
interface ProductOfferViewSectionPluginInterface
{
    /**
     * Specification:
     * - Returns template for section rendering.
     *
     * @api
     *
     * @return string
     */
    public function getTemplate(): string;

    /**
     * Specification:
     * - Returns data that is necessary to render the section template.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array<string, mixed>
     */
    public function getData(ProductOfferTransfer $productOfferTransfer): array;
}
