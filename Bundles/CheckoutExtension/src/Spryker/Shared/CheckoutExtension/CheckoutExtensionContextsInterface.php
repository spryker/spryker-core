<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CheckoutExtension;

interface CheckoutExtensionContextsInterface
{
    /**
     * Specification:
     * - Defines the Checkout Context, which applies when a customer initiates a new order.
     * - Enables workflows to distinguish new order processing from other order-related operations.
     * - The order is being created for the first time, not modified.
     * - Includes standard checkout steps such as cart review, payment, and confirmation.
     * - Enables plugins to execute context-specific logic related to new order creation.
     *
     * @api
     *
     * @var string
     */
    public const CONTEXT_CHECKOUT = 'checkout';
}
