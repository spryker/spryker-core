<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
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

    /**
     * Specification:
     * - Marks product bundle as discontinued if one of bundled products is discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function markProductBundleAsDiscontinuedByBundledProducts(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * Specification:
     * - Checks discontinued status of bundled products related to discontinued product.
     * - Returns ProductDiscontinuedResponseTransfer with isSuccessful=true if all bundled products are not discontinued.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer
     */
    public function checkBundledProducts(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ProductDiscontinuedResponseTransfer;
}
