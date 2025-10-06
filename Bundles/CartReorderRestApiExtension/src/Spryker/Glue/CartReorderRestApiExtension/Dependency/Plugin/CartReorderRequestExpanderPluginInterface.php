<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\RestUserTransfer;

/**
 * Use this plugin interface to expand `CartReorderRequestTransfer` before reorder.
 */
interface CartReorderRequestExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided `CartReorderRequestTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderRequestTransfer
     */
    public function expand(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        RestUserTransfer $restUserTransfer
    ): CartReorderRequestTransfer;
}
