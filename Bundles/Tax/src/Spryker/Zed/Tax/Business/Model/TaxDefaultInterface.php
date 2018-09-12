<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

interface TaxDefaultInterface
{
    /**
     * @return string
     */
    public function getDefaultCountryIso2Code();

    /**
     * @return float
     */
    public function getDefaultTaxRate();
}
