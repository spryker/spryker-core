<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeTransfer;

interface ProductAlternativePluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function executePostProductAlternativePlugins(ProductAlternativeTransfer $productAlternativeTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function executeDeleteProductAlternativePlugins(ProductAlternativeTransfer $productAlternativeTransfer): void;
}
