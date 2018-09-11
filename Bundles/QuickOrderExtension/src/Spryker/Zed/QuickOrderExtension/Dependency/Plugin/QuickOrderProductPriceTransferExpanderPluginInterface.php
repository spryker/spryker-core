<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuickOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuickOrderProductPriceTransfer;

interface QuickOrderProductPriceTransferExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided QuickOrderProductPriceTransfer with prices data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer
     *
     * @return void
     */
    public function expand(QuickOrderProductPriceTransfer $quickOrderProductPriceTransfer): void;
}
