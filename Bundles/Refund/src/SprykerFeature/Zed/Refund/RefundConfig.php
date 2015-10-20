<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface;
use Symfony\Component\Intl\Exception\NotImplementedException;

class RefundConfig extends AbstractBundleConfig
{

    /**
     * @return PaymentDataPluginInterface
     */
    public function getPaymentDataPlugin() {
        throw new NotImplementedException('No Payment Data Plugin Provided. Please implement on project level.');
    }

}
