<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Persistence\SalesRepositoryInterface;

class DuplicateOrderChecker implements DuplicateOrderCheckerInterface
{
    protected const GLOSSARY_KEY_CHECKOUT_DUPLICATE_ORDER = 'checkout.order.duplicate';
    protected const DUPLICATE_ORDER_REFERENCE_PARAMETER = '{{reference}}';

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
        if (!$this->isOrderExists($quoteTransfer)) {
            return true;
        }

        $this->setCheckoutResponseData($quoteTransfer, $checkoutResponseTransfer);

        return false;
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

        $customerTransfer = $quoteTransfer->getCustomer();
        if (!$customerTransfer || !$customerTransfer->getCustomerReference()) {
            return false;
        }

        return (bool)$this->salesRepository->findCustomerOrderIdByOrderReference(
            $customerTransfer->getCustomerReference(),
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

        if ($quoteTransfer->getIsOrderPlacedSuccessfully() === true) {
            return;
        }

        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setMessage(static::GLOSSARY_KEY_CHECKOUT_DUPLICATE_ORDER)
            ->setParameters([
                static::DUPLICATE_ORDER_REFERENCE_PARAMETER => $quoteTransfer->getOrderReference(),
            ]);

        $checkoutResponseTransfer
            ->setIsSuccess(false)
            ->addError($checkoutErrorTransfer);
    }
}
