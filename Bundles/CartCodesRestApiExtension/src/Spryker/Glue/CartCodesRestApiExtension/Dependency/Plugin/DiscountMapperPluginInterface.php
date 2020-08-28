<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartCodesRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\RestDiscountsAttributesTransfer;

/**
 * Provides extension point to map `DiscountTransfer` to Glue resource attributes `RestDiscountsAttributesTransfer`.
 *
 * Use this plugin to map additional data from `DiscountTransfer` to `cart-rules` or `vouchers` resource attributes.
 */
interface DiscountMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `DiscountTransfer` to `RestDiscountsAttributesTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestDiscountsAttributesTransfer
     */
    public function mapDiscountTransferToRestDiscountsAttributesTransfer(
        DiscountTransfer $discountTransfer,
        RestDiscountsAttributesTransfer $restDiscountsAttributesTransfer
    ): RestDiscountsAttributesTransfer;
}
