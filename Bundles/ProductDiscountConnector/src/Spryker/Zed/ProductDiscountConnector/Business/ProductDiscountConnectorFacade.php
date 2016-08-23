<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductDiscountConnector\Business\ProductDiscountConnectorBusinessFactory getFactory()
 */
class ProductDiscountConnectorFacade extends AbstractFacade
{

    /**
     * Specification:
     * - Build all product variants by abstract sku
     * - Look for attribute in any variants
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isProductAttributeSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        return $this->getFactory()
            ->createProductAttributeDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * Specification:
     * - Build all product variants by abstract sku
     * - Look for attribute in any variants
     * - Collect all matching items
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectByProductAttribute(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createProductAttributeCollector()
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     *  Specification:
     * - Query and return array list of attributes
     *
     * @api
     *
     * @return array|string[]
     */
    public function getAttributeTypes()
    {
        return $this->getFactory()
            ->createAttributeProvider()
            ->getAllAttributeTypes();
    }

}
