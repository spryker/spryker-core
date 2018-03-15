<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Business\Model;

use Generated\Shared\Transfer\OrderListTransfer;
use Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface;

class OfferReader implements OfferReaderInterface
{
    /**
     * @var \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @param \Spryker\Zed\Offer\Dependency\Facade\OfferToSalesFacadeInterface $salesFacade
     */
    public function __construct(
        OfferToSalesFacadeInterface $salesFacade
    ) {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderListTransfer $orderListTransfer
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOfferList(OrderListTransfer $orderListTransfer): OrderListTransfer
    {
        $orderListTransfer->setIsOffer(true);

        return $this->salesFacade
            ->getCustomerOrders($orderListTransfer, $orderListTransfer->getIdCustomer());
    }
}
