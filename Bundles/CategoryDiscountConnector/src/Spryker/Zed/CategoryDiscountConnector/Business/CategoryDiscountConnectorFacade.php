<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CategoryDiscountConnector\Business\CategoryDiscountConnectorBusinessFactory getFactory()
 */
class CategoryDiscountConnectorFacade extends AbstractFacade implements CategoryDiscountConnectorFacadeInterface
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
    public function isCategorySatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        return $this->getFactory()
            ->createCategoryDecisionRuleChecker()
            ->isCategorySatisfiedBy($quoteTransfer, $itemTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function getDiscountableItemsByCategory(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array
    {
        return $this->getFactory()
            ->createDiscountableItemReader()
            ->getDiscountableItemsByCategory($quoteTransfer, $clauseTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getCategoryNamesIndexedByCategoryKey(): array
    {
        return $this->getFactory()
            ->createCategoryReader()
            ->getCategoryNamesIndexedByCategoryKey();
    }
}
