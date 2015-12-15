<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Refund;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Refund\Dependency\Plugin\PaymentDataPluginInterface;
use Symfony\Component\Intl\Exception\NotImplementedException;

class RefundConfig extends AbstractBundleConfig
{

    /**
     * @throws NotImplementedException
     *
     * @return PaymentDataPluginInterface
     */
    public function getPaymentDataPlugin()
    {
        throw new NotImplementedException('No Payment Data Plugin Provided. Please implement on project level.');
    }

}
