<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\PaymentConfig;

class PaymentMethodReader implements PaymentMethodReaderInterface
{
    /**
     * @var \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[]
     */
    protected $paymentMethodFilterPlugins;

    /**
     * @var \Spryker\Zed\Payment\PaymentConfig
     */
    protected $paymentConfig;

    /**
     * @param \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[] $paymentMethodFilterPlugins
     * @param \Spryker\Zed\Payment\PaymentConfig $paymentConfig
     */
    public function __construct(array $paymentMethodFilterPlugins, PaymentConfig $paymentConfig)
    {
        $this->paymentMethodFilterPlugins = $paymentMethodFilterPlugins;
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        $paymentMethodsTransfer = $this->findPaymentMethods();
        $paymentMethodsTransfer = $this->applyFilterPlugins($paymentMethodsTransfer, $quoteTransfer);

        return $paymentMethodsTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function findPaymentMethods()
    {
        $paymentMethodsTransfer = new PaymentMethodsTransfer();
        $paymentStateMachineMappings = $this->paymentConfig->getPaymentStatemachineMappings();

        foreach ($paymentStateMachineMappings as $methodKey => $process) {
            $paymentMethodTransfer = $this->createPaymentMethodTransfer($methodKey);
            $paymentMethodsTransfer->addMethod($paymentMethodTransfer);
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    protected function applyFilterPlugins(PaymentMethodsTransfer $paymentMethodsTransfer, $quoteTransfer)
    {
        foreach ($this->paymentMethodFilterPlugins as $paymentMethodFilterPlugin) {
            $paymentMethodsTransfer = $paymentMethodFilterPlugin->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param string $methodKey
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function createPaymentMethodTransfer($methodKey)
    {
        $paymentMethodTransfer = new PaymentMethodTransfer();
        $paymentMethodTransfer->setMethodName($methodKey);

        return $paymentMethodTransfer;
    }
}
