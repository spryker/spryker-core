<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payment\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutor;
use Spryker\Zed\Payment\PaymentDependencyProvider;

class PaymentBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Payment\Business\Checkout\PaymentPluginExecutor
     */
    public function createCheckoutPaymentPluginExecutor()
    {
        return new PaymentPluginExecutor($this->getCheckoutPlugins());
    }

    /**
     * @return array
     */
    public function getCheckoutPlugins()
    {
         return $this->getProvidedDependency(PaymentDependencyProvider::CHECKOUT_PLUGINS);
    }
}
