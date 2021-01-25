<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax;

use Spryker\Shared\Tax\TaxConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TaxConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return float
     */
    public function getDefaultTaxRate()
    {
        return $this->get(TaxConstants::DEFAULT_TAX_RATE, 0);
    }
}
