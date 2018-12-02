<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Provider;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderCollectionTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderCollectionTransfer
     */
    public function getAvailablePaymentProviders(QuoteTransfer $quoteTransfer): PaymentProviderCollectionTransfer
    {
        $paymentProviderCollectionTransfer = new PaymentProviderCollectionTransfer();
        $filteredPaymentProviderList = $this->getFilteredPaymentProviderList($quoteTransfer);

        foreach ($filteredPaymentProviderList as $providerName => $paymentMethods) {
            $paymentProviderTransfer = (new PaymentProviderTransfer())
                ->setProviderName($providerName);
            foreach ($paymentMethods as $paymentMethod) {
                $paymentProviderTransfer->addPaymentMethod($paymentMethod);
            }
            $paymentProviderCollectionTransfer->addPaymentProvider($paymentProviderTransfer);
        }

        return $paymentProviderCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getFilteredPaymentProviderList(QuoteTransfer $quoteTransfer): array
    {
        $filteredPaymentProviderList = [];
        $availablePaymentMethodSelections = $this->getAvailablePaymentMethodSelections($quoteTransfer);
        $salesPaymentMethodTypesCollection = $this->paymentRepository->getSalesPaymentMethodTypesCollection();

        foreach ($salesPaymentMethodTypesCollection->getSalesPaymentMethodTypes() as $salesPaymentMethodTypeTransfer) {
            $salesPaymentMethodTypeSelection = $this->buildSelectionStringBySalesPaymentMethodTypeTransfer($salesPaymentMethodTypeTransfer);
            if (in_array($salesPaymentMethodTypeSelection, $availablePaymentMethodSelections)) {
                $paymentProvider = $salesPaymentMethodTypeTransfer->getPaymentProvider();
                if (!isset($filteredPaymentProviderList[$paymentProvider])) {
                    $filteredPaymentProviderList[$paymentProvider] = [];
                }
                $paymentMethodTransfer = (new PaymentMethodTransfer())
                    ->setMethodName($salesPaymentMethodTypeTransfer->getPaymentMethod())
                    ->setPaymentSelection($salesPaymentMethodTypeSelection);
                $filteredPaymentProviderList[$paymentProvider][] = $paymentMethodTransfer;
            }
        }

        return $filteredPaymentProviderList;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function getAvailablePaymentMethodSelections(QuoteTransfer $quoteTransfer): array
    {
        $availableMethods = $this->paymentMethodReader->getAvailableMethods($quoteTransfer);

        $availablePaymentMethodSelections = [];
        foreach ($availableMethods->getMethods() as $availableMethod) {
            $availablePaymentMethodSelections[] = $availableMethod->getMethodName();
        }

        return $availablePaymentMethodSelections;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
     *
     * @return string
     */
    protected function buildSelectionStringBySalesPaymentMethodTypeTransfer(SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer): string
    {
        $paymentProvider = lcfirst($salesPaymentMethodTypeTransfer->getPaymentProvider());
        $paymentMethod = str_replace(' ', '', ucwords($salesPaymentMethodTypeTransfer->getPaymentMethod()));

        return $paymentProvider . $paymentMethod;
    }
}
