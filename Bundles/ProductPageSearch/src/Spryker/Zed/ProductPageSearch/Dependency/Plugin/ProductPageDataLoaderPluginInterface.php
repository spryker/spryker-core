<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Plugin;

use Generated\Shared\Transfer\ProductPageLoadTransfer;

interface ProductPageDataLoaderPluginInterface
{

    /**
     * @param ProductPageLoadTransfer $loadTransfer
     *
     * @return void
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer);

    /**
     * @return string
     */
    public function getProductPageType();
}
