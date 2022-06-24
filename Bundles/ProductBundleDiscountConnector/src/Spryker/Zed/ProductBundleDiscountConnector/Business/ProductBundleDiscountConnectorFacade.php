<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundleDiscountConnector\Business\ProductBundleDiscountConnectorBusinessFactory getFactory()
 */
class ProductBundleDiscountConnectorFacade extends AbstractFacade implements ProductBundleDiscountConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\DiscountableItemTransfer> $discountableItems
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function expandProductAttributeDiscountableItemCollectionWithBundledProducts(
        array $discountableItems,
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer
    ): array {
        return $this->getFactory()
            ->createProductAttributeDiscountableItemsCollectionExpander()
            ->expandProductAttributeDiscountableItemCollectionWithBundledProducts($discountableItems, $quoteTransfer, $clauseTransfer);
    }
}
