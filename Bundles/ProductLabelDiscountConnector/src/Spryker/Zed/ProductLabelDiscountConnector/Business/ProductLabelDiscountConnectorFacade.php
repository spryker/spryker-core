<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductLabelDiscountConnector\Business\ProductLabelDiscountConnectorBusinessFactory getFactory()
 */
class ProductLabelDiscountConnectorFacade extends AbstractFacade implements ProductLabelDiscountConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isProductLabelSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createProductLabelDecisionRule()
            ->isSatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collectByProductLabel(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer)
    {
        return $this->getFactory()
            ->createProductLabelCollector()
            ->collect($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function findAllLabels()
    {
        return $this->getFactory()
            ->createLabelProvider()
            ->findAllLabels();
    }
}
