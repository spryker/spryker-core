<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;

interface PostProductViewAvailabilityStorageExpandPluginInterface
{
    /**
     * Specification:
     * - Executed after product view availability expansion.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function postExpand(ProductViewTransfer $productViewTransfer): ProductViewTransfer;
}
