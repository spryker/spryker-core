<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductDiscontinuedTransfer;

interface PostProductDiscontinuePluginInterface
{
    /**
     * Specification:
     *  - Executes after product marked as discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function execute(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void;
}
