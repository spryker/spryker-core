<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PersistentCart;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PersistentCartConstants
{
    /**
     * Specification:
     * - Customer reference prefix for anonymous customer cart.
     *
     * @api
     */
    public const PERSISTENT_CART_ANONYMOUS_PREFIX = 'PERSISTENT_CART:PERSISTENT_CART_ANONYMOUS_PREFIX';
}
