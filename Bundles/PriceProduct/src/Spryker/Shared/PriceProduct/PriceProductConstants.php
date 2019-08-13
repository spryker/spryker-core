<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PriceProduct;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PriceProductConstants
{
    /**
     * @uses \Spryker\Shared\PriceProductStorage\PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT
     */
    public const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    public const DELETE_ORPHAN_PRICES_MODE_ENABLED = 'PRICE_PRODUCT::DELETE_ORPHAN_PRICES_MODE_ENABLED';
}
