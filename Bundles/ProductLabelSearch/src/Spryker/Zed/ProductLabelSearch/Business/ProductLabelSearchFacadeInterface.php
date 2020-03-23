<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Business;

use Generated\Shared\Transfer\ProductPageLoadTransfer;

interface ProductLabelSearchFacadeInterface
{
    /**
     * Specification:
     * - Expand product page load transfer with product label ids mapped by id product abstract and store name
     * - Returns a product page load transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageData(ProductPageLoadTransfer $loadTransfer);
}
