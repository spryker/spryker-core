<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface;
use Spryker\Zed\Offer\OfferConfig;

class OfferReader implements OfferReaderInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Offer\OfferConfig
     */
    protected $offerConfig;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Offer\OfferConfig $offerConfig
     */
    public function __construct(
        OfferToSalesFacadeInterface $salesFacade,
        OfferConfig $offerConfig
    ) {
        $this->salesFacade = $salesFacade;
        $this->offerConfig = $offerConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOfferList(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->setType(
            $this->offerConfig->getOrderTypeOffer()
        );

        return $this->salesFacade
            ->getCustomerOrders($orderListTransfer, $orderListTransfer->getIdCustomer());
    }
}
