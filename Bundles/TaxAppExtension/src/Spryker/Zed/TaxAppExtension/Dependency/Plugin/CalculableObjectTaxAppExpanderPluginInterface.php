<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxAppExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CalculableObjectTransfer;

/**
 * Implement this plugin if you want to expand `CalculableObjectTransfer` with additional data.
 */
interface CalculableObjectTaxAppExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands CalculableObjectTransfer and its contents with Tax App necessary data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expand(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer;
}
