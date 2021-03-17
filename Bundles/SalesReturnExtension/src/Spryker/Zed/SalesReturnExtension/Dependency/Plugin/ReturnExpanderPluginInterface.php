<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReturnTransfer;

/**
 * Allows to expand return transfer with additional data.
 */
interface ReturnExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands a return transfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function expand(ReturnTransfer $returnTransfer): ReturnTransfer;
}
