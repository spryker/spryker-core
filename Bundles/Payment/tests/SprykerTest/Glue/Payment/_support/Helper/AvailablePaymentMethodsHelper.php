<?php

namespace SprykerTest\Glue\Payment\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailablePaymentMethodsHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function haveAvailablePaymentProvider(): PaymentProviderTransfer
    {
        $availableMethods = $this->getLocator()->payment()->facade()->getAvailablePaymentProviders();

        return current($availableMethods->getPaymentProviders());
    }
}
