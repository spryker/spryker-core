<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Writer;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface;

class OrderCustomReferenceWriter implements OrderCustomReferenceWriterInterface
{
    /**
     * @var \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface
     */
    protected $orderCustomReferenceEntityManager;

    /**
     * @param \Spryker\Zed\OrderCustomReference\Persistence\OrderCustomReferenceEntityManagerInterface $orderCustomReferenceEntityManager
     */
    public function __construct(OrderCustomReferenceEntityManagerInterface $orderCustomReferenceEntityManager)
    {
        $this->orderCustomReferenceEntityManager = $orderCustomReferenceEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderCustomReference(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        if ($quoteTransfer->getOrderCustomReference() && $saveOrderTransfer->getIdSalesOrder()) {
            $this->orderCustomReferenceEntityManager
                ->saveOrderCustomReference(
                    $saveOrderTransfer->getIdSalesOrder(),
                    $quoteTransfer->getOrderCustomReference()
                );
        }
    }

    /**
     * @param string $orderCustomReference
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function updateOrderCustomReference(string $orderCustomReference, OrderTransfer $orderTransfer): void
    {
        if ($orderTransfer->getIdSalesOrder()) {
            $this->orderCustomReferenceEntityManager
                ->saveOrderCustomReference(
                    $orderTransfer->getIdSalesOrder(),
                    $orderCustomReference
                );
        }
    }
}
