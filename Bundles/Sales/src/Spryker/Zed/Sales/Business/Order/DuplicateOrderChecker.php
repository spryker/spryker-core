<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class DuplicateOrderChecker implements DuplicateOrderCheckerInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface
     */
    protected $salesRepository;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface $salesRepository
     */
    public function __construct(SalesRepositoryInterface $salesRepository)
    {
        $this->salesRepository = $salesRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function checkDuplicateOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if ($this->isOrderExists($quoteTransfer)) {
            $this->setCheckoutResponseData($quoteTransfer, $checkoutResponseTransfer);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isOrderExists(QuoteTransfer $quoteTransfer): bool
    {
        if (!$quoteTransfer->getOrderReference()) {
            return false;
        }

        if ($quoteTransfer->getIsOrderPlacedSuccessfully() === null) {
            return false;
        }

        return (bool)$this->salesRepository->findCustomerOrderIdByOrderReference(
            $quoteTransfer->getCustomer()->getCustomerReference(),
            $quoteTransfer->getOrderReference()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function setCheckoutResponseData(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $checkoutResponseTransfer->getSaveOrder()->setOrderReference(
            $quoteTransfer->getOrderReference()
        );

        if ($quoteTransfer->getIsOrderPlacedSuccessfully() === false) {
            $checkoutResponseTransfer->setIsSuccess(false);
        }
    }
}
