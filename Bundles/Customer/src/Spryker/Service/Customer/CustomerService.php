<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Customer;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Customer\Items\ItemHasOwnShipmentTransferCheckerInterface;

/**
 * @deprecated Remove service layer after multiple shipment will be released.
 *
 * @method \Spryker\Service\Customer\CustomerServiceFactory getFactory()
 */
class CustomerService extends AbstractService implements CustomerServiceInterface
{
    /**
     * @var \Spryker\Service\Customer\Items\ItemHasOwnShipmentTransferCheckerInterface
     */
    protected $itemHasOwnShipmentTransferChecker;

    /**
     * @return \Spryker\Service\Customer\Items\ItemHasOwnShipmentTransferCheckerInterface
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
