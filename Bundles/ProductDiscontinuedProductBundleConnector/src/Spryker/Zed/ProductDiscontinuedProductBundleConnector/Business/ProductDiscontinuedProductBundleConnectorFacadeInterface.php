<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business;

use Generated\Shared\Transfer\ProductDiscontinuedTransfer;

interface ProductDiscontinuedProductBundleConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Find bundle products related to discontinued simple.
     *  - Mark related bundle product as discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function markRelatedBundleAsDiscontinued(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void;
}
