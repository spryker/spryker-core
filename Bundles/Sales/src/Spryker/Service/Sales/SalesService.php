<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Sales\Shipment;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Sales\Items\ItemHasOwnShipmentTransferCheckerInterface;

/**
 * @method \Spryker\Service\Sales\SalesServiceFactory getFactory()
 */
class SalesService extends AbstractService implements SalesServiceInterface
{
    /**
     * @var \Spryker\Service\Sales\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    protected $itemHasOwnShipmentTransferChecker;

    /**
     * @return \Spryker\Service\Sales\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    protected function getItemHasOwnShipmentTransferChecker(): ItemHasOwnShipmentTransferCheckerInterface
    {
        if ($this->itemHasOwnShipmentTransferChecker === null) {
            $this->itemHasOwnShipmentTransferChecker = $this->getFactory()->createSplitDeliveryEnabledChecker();
        }

        return $this->itemHasOwnShipmentTransferChecker;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteItemHasOwnShipmentTransfer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getItemHasOwnShipmentTransferChecker()->checkByQuote($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkOrderItemHasOwnShipmentTransfer(OrderTransfer $orderTransfer): bool
    {
        return $this->getItemHasOwnShipmentTransferChecker()->checkByOrder($orderTransfer);
    }
}
