<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Provider;

use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentProviderReader implements PaymentProviderReaderInterface
{
    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface
     */
    protected $paymentMethodReader;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     * @param \Spryker\Zed\Payment\Business\Method\PaymentMethodReaderInterface $paymentMethodReader
     */
    public function __construct(
        PaymentRepositoryInterface $paymentRepository,
        PaymentMethodReaderInterface $paymentMethodReader
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->paymentMethodReader = $paymentMethodReader;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getAvailablePaymentProviders(): PaymentProviderCollectionTransfer
    {
        $paymentProviderCollectionTransfer = new PaymentProviderCollectionTransfer();
        $groupedSalesPaymentMethodTypesList = $this->getSalesPaymentMethodTypesGroupedByPaymentProviderName();

        foreach ($groupedSalesPaymentMethodTypesList as $paymentProviderName => $salesPaymentMethodTypes) {
            $paymentProviderTransfer = (new PaymentProviderTransfer())
                ->setName($paymentProviderName);
            foreach ($salesPaymentMethodTypes as $salesPaymentMethodType) {
                /** @var \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer $salesPaymentMethodType */
                $paymentProviderTransfer->addPaymentMethod($salesPaymentMethodType->getPaymentMethod());
            }

            $paymentProviderCollectionTransfer->addPaymentProvider($paymentProviderTransfer);
        }

        return $paymentProviderCollectionTransfer;
    }

    /**
     * @return array
     */
    protected function getSalesPaymentMethodTypesGroupedByPaymentProviderName(): array
    {
        $groupedSalesPaymentMethodTypesList = [];
        $salesPaymentMethodTypesCollection = $this->paymentRepository->getSalesPaymentMethodTypesCollection();

        $salesPaymentMethodTypes = $salesPaymentMethodTypesCollection->getSalesPaymentMethodTypes();
        foreach ($salesPaymentMethodTypes as $salesPaymentMethodTypeTransfer) {
            $paymentProviderName = $salesPaymentMethodTypeTransfer->getPaymentProvider()->getName();
            if (!isset($groupedSalesPaymentMethodTypesList[$paymentProviderName])) {
                $groupedSalesPaymentMethodTypesList[$paymentProviderName] = [];
            }
            $groupedSalesPaymentMethodTypesList[$paymentProviderName][] = $salesPaymentMethodTypeTransfer;
        }

        return $groupedSalesPaymentMethodTypesList;
    }
}
