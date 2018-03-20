<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\SalesExtension\Dependency\Plugin\PreSaveOrderHydratePluginInterface;

class IsOfferPreSaveHydrateOrderPlugin implements PreSaveOrderHydratePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function hydrate(QuoteTransfer $quoteTransfer, SpySalesOrder $salesOrderEntity): SpySalesOrder
    {
        $salesOrderEntity->setIsOffer($quoteTransfer->getIsOffer());

        return $salesOrderEntity;
    }
}
