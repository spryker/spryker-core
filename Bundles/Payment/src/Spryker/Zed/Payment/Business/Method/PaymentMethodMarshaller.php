<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use ArrayObject;
use Generated\Shared\Transfer\PaymentInformationTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\PaymentConfig;

class PaymentMethodMarshaller
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
        $paymentMethods = $this->getAllPossibleMethods();

        foreach ($this->paymentMethodFilterPlugins as $paymentMethodFilterPlugin) {
            $paymentMethods = $paymentMethodFilterPlugin->filterPaymentMethods($paymentMethods, $quoteTransfer);
        }

        $paymentMethodsTransfer = new PaymentMethodsTransfer();
        $paymentMethodsTransfer->setAvailableMethods($paymentMethods);

        return $paymentMethodsTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject
     */
    protected function getAllPossibleMethods()
    {
        $result = new ArrayObject();

        $paymentStatemachineMappings = $this->paymentConfig->getPaymentStatemachineMappings();

        foreach ($paymentStatemachineMappings as $methodKey => $process) {
            $paymentMethodTransfer = $this->createPaymentMethodTransfer($methodKey);
            $result[] = $paymentMethodTransfer;
        }

        return $result;
    }

    /**
     * @param string $method
     *
     * @return \Generated\Shared\Transfer\PaymentInformationTransfer
     */
    protected function createPaymentMethodTransfer($method)
    {
        $paymentMethodTransfer = new PaymentInformationTransfer();
        $paymentMethodTransfer->setProvider('');
        $paymentMethodTransfer->setMethod($method);

        return $paymentMethodTransfer;
    }

}
