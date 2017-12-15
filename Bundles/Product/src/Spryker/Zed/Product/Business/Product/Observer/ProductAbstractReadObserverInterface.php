<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Observer;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractReadObserverInterface
{
    /**
     * Specification:
     * - Executed on retrieved persisted abstract product data.
     * - Notifies registered observers in chain of responsibility.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function read(ProductAbstractTransfer $productAbstractTransfer);
}
