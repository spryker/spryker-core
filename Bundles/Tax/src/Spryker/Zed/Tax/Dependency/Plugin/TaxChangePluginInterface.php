<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Dependency\Plugin;

interface TaxChangePluginInterface
{

    /**
     * @param int $idTaxRate
     */
    public function handleTaxRateChange($idTaxRate);

    /**
     * @param int $idTaxSet
     */
    public function handleTaxSetChange($idTaxSet);

}
