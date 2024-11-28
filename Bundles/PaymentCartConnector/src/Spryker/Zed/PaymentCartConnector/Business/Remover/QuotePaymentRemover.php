<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector\Business\Remover;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig;

class QuotePaymentRemover implements QuotePaymentRemoverInterface
{
    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_ADD
     *
     * @var string
     */
    protected const OPERATION_ADD = 'add';

    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_REMOVE
     *
     * @var string
     */
    protected const OPERATION_REMOVE = 'remove';

    /**
     * @var \Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig
     */
    protected PaymentCartConnectorConfig $config;

    /**
     * @param \Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig $config
     */
    public function __construct(PaymentCartConnectorConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeQuotePayment(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $excludedPaymentMethodMap = array_combine(
            $this->config->getExcludedPaymentMethods(),
            $this->config->getExcludedPaymentMethods(),
        );

        $quoteTransfer = $this->removePayment($quoteTransfer, $excludedPaymentMethodMap);

        return $this->removePayments($quoteTransfer, $excludedPaymentMethodMap);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, string> $excludedPaymentMethodMap
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removePayment(QuoteTransfer $quoteTransfer, array $excludedPaymentMethodMap = []): QuoteTransfer
    {
        $paymentTransfer = $quoteTransfer->getPayment();
        if ($paymentTransfer && !$this->isPaymentMethodExcluded($excludedPaymentMethodMap, $paymentTransfer)) {
            $quoteTransfer->setPayment(null);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array<string, string> $excludedPaymentMethodMap
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removePayments(QuoteTransfer $quoteTransfer, array $excludedPaymentMethodMap = []): QuoteTransfer
    {
        $excludedPaymentTransfers = new ArrayObject();

        $paymentTransfers = $quoteTransfer->getPayments();
        foreach ($paymentTransfers as $paymentTransfer) {
            if ($this->isPaymentMethodExcluded($excludedPaymentMethodMap, $paymentTransfer)) {
                $excludedPaymentTransfers->append($paymentTransfer);
            }
        }

        return $quoteTransfer->setPayments($excludedPaymentTransfers);
    }

    /**
     * @param array<string, string> $excludedPaymentMethodKeyMap
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return bool
     */
    protected function isPaymentMethodExcluded(
        array $excludedPaymentMethodKeyMap,
        PaymentTransfer $paymentTransfer
    ): bool {
        return isset($excludedPaymentMethodKeyMap[$paymentTransfer->getPaymentMethod()]);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function removeQuotePaymentOnCartChange(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        if (
            $cartChangeTransfer->getOperation() !== static::OPERATION_ADD
            && $cartChangeTransfer->getOperation() !== static::OPERATION_REMOVE
        ) {
            return $cartChangeTransfer;
        }

        $quoteTransfer = $this->removePayment($cartChangeTransfer->getQuoteOrFail());
        $quoteTransfer = $this->removePayments($quoteTransfer);

        return $cartChangeTransfer->setQuote($quoteTransfer);
    }
}
