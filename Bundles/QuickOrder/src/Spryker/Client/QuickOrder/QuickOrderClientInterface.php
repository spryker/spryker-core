<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface QuickOrderClientInterface
{
    /**
     * Specification:
     * - Returns ProductConcreteTransfer filled with data provided by plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
