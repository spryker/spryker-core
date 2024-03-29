<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Calculation;

interface CalculationPriceMode
{
    /**
     * @var string
     */
    public const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var string
     */
    public const PRICE_MODE_GROSS = 'GROSS_MODE';
}
