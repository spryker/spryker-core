<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Dependency\Facade;

interface TaxProductConnectorToTaxInterface
{
    /**
     * @return string
     */
    public function getDefaultTaxCountryIso2Code(): string;

    /**
     * @return float
     */
    public function getDefaultTaxRate(): float;
}
