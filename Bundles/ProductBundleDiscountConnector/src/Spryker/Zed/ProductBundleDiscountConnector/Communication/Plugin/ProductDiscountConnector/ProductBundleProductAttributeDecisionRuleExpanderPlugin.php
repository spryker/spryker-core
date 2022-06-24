<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleDiscountConnector\Communication\Plugin\ProductDiscountConnector;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscountConnectorExtension\Dependency\Plugin\ProductAttributeDecisionRuleExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductBundleDiscountConnector\ProductBundleDiscountConnectorConfig getConfig()
 * @method \Spryker\Zed\ProductBundleDiscountConnector\Business\ProductBundleDiscountConnectorFacadeInterface getFacade()
 */
class ProductBundleProductAttributeDecisionRuleExpanderPlugin extends AbstractPlugin implements ProductAttributeDecisionRuleExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `ItemTransfer` is not a part of a bundle.
     * - Returns `true` if product part of the bundle, `false` otherwise
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $currentItemTransfer, ClauseTransfer $clauseTransfer): bool
    {
        return $currentItemTransfer->getRelatedBundleItemIdentifier() === null;
    }
}
