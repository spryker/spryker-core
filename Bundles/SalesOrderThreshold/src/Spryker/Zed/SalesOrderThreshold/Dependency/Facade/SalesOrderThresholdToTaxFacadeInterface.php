<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThreshold\Dependency\Facade;

interface SalesOrderThresholdToTaxFacadeInterface
{
    /**
     * @return float
     */
    public function getDefaultTaxRate();

    /**
     * @return string
     */
    public function getDefaultTaxCountryIso2Code();
}
