<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpySalesOrderEntityTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\PreSaveOrderPluginInterface;

/**
 * @method \Spryker\Zed\Offer\OfferConfig getConfig()
 */
class IsOfferPreSaveHydrateOrderPlugin extends AbstractPlugin implements PreSaveOrderPluginInterface
{
    /**
     * {@inheritdoc}
     * //TODO: check if it is needed.
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SpySalesOrderEntityTransfer $salesOrderEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpySalesOrderEntityTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer, SpySalesOrderEntityTransfer $salesOrderEntityTransfer): SpySalesOrderEntityTransfer
    {
        $salesOrderEntityTransfer->setType(
            $this->getConfig()->getOrderTypeOffer()
        );

        return $salesOrderEntityTransfer;
    }
}
