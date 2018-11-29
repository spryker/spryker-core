<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\ProductOption;

use Generated\Shared\Transfer\ProductOptionGroupTransfer;

interface PreRemoveProductOptionValuePluginExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return void
     */
    public function executePreRemoveOptionValuesPlugins(ProductOptionGroupTransfer $productOptionGroupTransfer): void;
}
