<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CalculableObjectTransfer;

interface CalculationPluginInterface
{
    /**
     * Specification:
     * - Allows to add custom calculation logic for original quote and order.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer);
}
