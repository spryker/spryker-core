<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class CalculationConfig extends AbstractBundleConfig
{

    const ERROR_CODE_CART_AMOUNT_DIFFERENT = '4003';

    const TAX_MODE_NET = 'tax mode net';
    const TAX_MODE_GROSS = 'tax mode gross';

    /**
     * @return bool
     */
    public function enableNewCalculators()
    {
        return true;
    }

}
