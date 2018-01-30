<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Business;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductValidityFacadeInterface
{
    /**
     * Specification:
     * - Finds products that are about to become valid/invalid for the current time.
     * - Products that are about to become active and are not published will be marked as 'active' in the database.
     * - Products that are about to become active and are not published will cause touching.
     * - Products that are about to become inactive and are published will be marked as 'inactive' in the database.
     * - Products that are about to become inactive and are published will cause touching.
     *
     * @api
     *
     * @return void
     */
    public function checkProductValidityDateRangeAndTouch(): void;

    /**
     * Specification:
     * - Hydrates product concrete validity (validFrom and validTo fields).
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function hydrateProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;

    /**
     * Specification:
     * - Persists validity dates from a product concrete to the product validity table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveProductValidity(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
