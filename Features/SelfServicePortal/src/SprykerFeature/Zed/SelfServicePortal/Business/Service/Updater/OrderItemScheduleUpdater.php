<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Updater;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Resolver\PaymentMethodResolverInterface;

class OrderItemScheduleUpdater implements OrderItemScheduleUpdaterInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_NO_ORDER_ITEMS_PROVIDED = 'self_service_portal.service.validation.no_order_items_provided';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_ORDER_NOT_FOUND = 'self_service_portal.service.validation.order_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_NO_PAYMENT_METHODS_FOUND = 'self_service_portal.service.validation.no_payment_methods_found';

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Service\Resolver\PaymentMethodResolverInterface $paymentMethodResolver
     */
    public function __construct(
        protected SalesFacadeInterface $salesFacade,
        protected PaymentMethodResolverInterface $paymentMethodResolver
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $salesOrderItemCollectionRequestTransfer->requireItems();

        $itemTransfer = $salesOrderItemCollectionRequestTransfer->getItems()->getIterator()->current();
        $orderTransfer = $this->salesFacade->findOrderByIdSalesOrder($itemTransfer->getFkSalesOrderOrFail());

        if ($orderTransfer === null) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_VALIDATION_ORDER_NOT_FOUND, ['%id%' => $itemTransfer->getFkSalesOrder()]);
        }

        if ($orderTransfer->getPayments()->count() === 0) {
            return $this->createErrorResponse(static::GLOSSARY_KEY_VALIDATION_NO_PAYMENT_METHODS_FOUND);
        }

        $paymentTransfer = $orderTransfer->getPayments()->offsetGet(0);
        $paymentMethod = $this->paymentMethodResolver->resolvePaymentMethod($itemTransfer, $paymentTransfer);

        $paymentTransfer->setPaymentSelection($paymentMethod);

        $quoteTransfer = (new QuoteTransfer())
            ->setPayments(new ArrayObject([$paymentTransfer]))
            ->setPayment($paymentTransfer);

        $salesOrderItemCollectionRequestTransfer->setQuote($quoteTransfer);

        return $this->salesFacade->updateSalesOrderItemCollectionByQuote($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @param string $errorMessage
     * @param array<string, mixed> $parameters
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    protected function createErrorResponse(string $errorMessage, array $parameters = []): SalesOrderItemCollectionResponseTransfer
    {
        $errorTransfer = (new ErrorTransfer())
            ->setMessage($errorMessage)
            ->setParameters($parameters);

        return (new SalesOrderItemCollectionResponseTransfer())
            ->addError($errorTransfer)
            ->setItems(new ArrayObject());
    }
}
