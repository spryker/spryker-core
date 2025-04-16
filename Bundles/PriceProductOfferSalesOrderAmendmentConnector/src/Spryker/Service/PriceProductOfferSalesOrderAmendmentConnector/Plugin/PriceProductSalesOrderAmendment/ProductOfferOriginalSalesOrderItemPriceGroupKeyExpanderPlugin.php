<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductOfferSalesOrderAmendmentConnector\Plugin\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface;

class ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPlugin extends AbstractPlugin implements OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const GROUP_KEY_DELIMITER = '_';

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
        if ($itemTransfer->getProductOfferReference()) {
            $groupKey = $groupKey . static::GROUP_KEY_DELIMITER . $itemTransfer->getProductOfferReference();
        }

        return $groupKey;
    }
}
