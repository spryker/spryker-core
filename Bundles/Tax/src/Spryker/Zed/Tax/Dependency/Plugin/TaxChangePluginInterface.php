<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Dependency\Plugin;

interface TaxChangePluginInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idTaxRate
     *
     * @return void
     */
    public function handleTaxRateChange($idTaxRate);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idTaxSet
     *
     * @return void
     */
    public function handleTaxSetChange($idTaxSet);
}
