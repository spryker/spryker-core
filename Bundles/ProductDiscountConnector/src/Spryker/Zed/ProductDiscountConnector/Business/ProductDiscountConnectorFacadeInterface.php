<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductDiscountConnectorFacadeInterface
{
    /**
     * Specification:
     * - Builds all product variants by abstract SKU.
     * - Looks for the attribute in any variants.
     * - Executes {@link \Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeDecisionRuleExpanderPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isProductAttributeSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Builds all product variants by abstract SKU.
     * - Looks for the attribute in any variants.
     * - Collects all matching items.
     * - Executes {@link \Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeCollectorExpanderPluginInterface} plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collectByProductAttribute(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Returns array list of attributes.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAttributeTypes();
}
