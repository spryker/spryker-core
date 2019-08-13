<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;

interface TaxProductConnectorFacadeInterface
{
    /**
     * Specification:
     * - Save tax set id to product abstract table
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function saveTaxSetToProductAbstract(ProductAbstractTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Read tax set from database and sets PriceProductTransfer on ProductAbstractTransfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapTaxSet(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     *  - Set tax rate for each item
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductItemTaxRate(QuoteTransfer $quoteTransfer);

    /**
     * Specification:
     *  - Returns response with tax set for abstract product.
     *  - If tax set is null - sets error message and isSuccess to false.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetResponseTransfer
     */
    public function getTaxSetForProductAbstract(ProductAbstractTransfer $productAbstractTransfer): TaxSetResponseTransfer;
}
