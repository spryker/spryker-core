<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Shipment;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ShipmentConstants
{
    public const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    public const PRICE_MODE_NET = 'NET_MODE';
    public const PRICE_MODE_GROSS = 'GROSS_MODE';

    public const OPTION_AMOUNT_PER_STORE = 'amount_per_store';
}
