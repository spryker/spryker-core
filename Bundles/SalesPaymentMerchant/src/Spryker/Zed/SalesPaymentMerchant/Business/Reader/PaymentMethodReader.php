<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodConditionsTransfer;
use Generated\Shared\Transfer\PaymentMethodCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\SalesPaymentConditionsTransfer;
use Generated\Shared\Transfer\SalesPaymentCriteriaTransfer;
use Generated\Shared\Transfer\SalesPaymentTransfer;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToPaymentFacadeInterface;
use Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesPaymentFacadeInterface;

class PaymentMethodReader implements PaymentMethodReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToPaymentFacadeInterface
     */
    protected SalesPaymentMerchantToPaymentFacadeInterface $paymentFacade;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesPaymentFacadeInterface
     */
    protected SalesPaymentMerchantToSalesPaymentFacadeInterface $salesPaymentFacade;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToPaymentFacadeInterface $paymentFacade
     * @param \Spryker\Zed\SalesPaymentMerchant\Dependency\Facade\SalesPaymentMerchantToSalesPaymentFacadeInterface $salesPaymentFacade
     */
    public function __construct(
        SalesPaymentMerchantToPaymentFacadeInterface $paymentFacade,
        SalesPaymentMerchantToSalesPaymentFacadeInterface $salesPaymentFacade
    ) {
        $this->paymentFacade = $paymentFacade;
        $this->salesPaymentFacade = $salesPaymentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getPaymentMethodForOrder(OrderTransfer $orderTransfer): PaymentMethodTransfer
    {
        $salesPaymentTransfer = $this->getSalesPaymentTransfer($orderTransfer);
        $paymentMethodKey = $this->paymentFacade->generatePaymentMethodKey(
            $salesPaymentTransfer->getPaymentProviderOrFail(),
            $salesPaymentTransfer->getPaymentMethodOrFail(),
        );

        $paymentMethodCriteriaTransfer = $this->createPaymentMethodCriteriaTransfer($paymentMethodKey);
        $paymentMethodTransferCollection = $this->paymentFacade->getPaymentMethodCollection($paymentMethodCriteriaTransfer);

        return $paymentMethodTransferCollection->getPaymentMethods()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentTransfer
     */
    protected function getSalesPaymentTransfer(OrderTransfer $orderTransfer): SalesPaymentTransfer
    {
        $salesPaymentConditionsTransfer = new SalesPaymentConditionsTransfer();
        $salesPaymentConditionsTransfer->addIdSalesOrder($orderTransfer->getIdSalesOrder());

        $salesPaymentCriteriaTransfer = new SalesPaymentCriteriaTransfer();
        $salesPaymentCriteriaTransfer->setSalesPaymentConditions($salesPaymentConditionsTransfer);

        $salesPaymentCollectionTransfer = $this->salesPaymentFacade->getSalesPaymentCollection($salesPaymentCriteriaTransfer);

        return $salesPaymentCollectionTransfer->getSalesPayments()->getIterator()->current();
    }

    /**
     * @param string $paymentMethodKey
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCriteriaTransfer
     */
    protected function createPaymentMethodCriteriaTransfer(string $paymentMethodKey): PaymentMethodCriteriaTransfer
    {
        $paymentMethodConditionsTransfer = new PaymentMethodConditionsTransfer();
        $paymentMethodConditionsTransfer->addPaymentMethodKey($paymentMethodKey);

        $paymentMethodCriteriaTransfer = new PaymentMethodCriteriaTransfer();
        $paymentMethodCriteriaTransfer->setPaymentMethodConditions($paymentMethodConditionsTransfer);

        return $paymentMethodCriteriaTransfer;
    }
}
