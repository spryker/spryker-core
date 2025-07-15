<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductOffer\Plugin\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface;

/**
 * @method \Spryker\Service\ProductOffer\ProductOfferServiceFactory getFactory()
 */
class ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPlugin extends AbstractPlugin implements OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided group key with product offer reference if `ItemTransfer.productOfferReference` is set.
     *
     * @api
     *
     * @param string $groupKey
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function expandGroupKey(string $groupKey, ItemTransfer $itemTransfer): string
    {
        return $this->getFactory()->createOriginalSalesOrderItemGroupKeyExpander()->expandGroupKey($groupKey, $itemTransfer);
    }
}
