<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductPageLoadTransfer;

interface ProductAbstractCollectionRefreshPluginInterface
{
    /**
     * Specification:
     * - Returns ProductPageLoadTransfer. ProductPageLoadTransfer.productAbstractIds is required.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function getProductPageLoadTransferForRefresh(): ProductPageLoadTransfer;
}
