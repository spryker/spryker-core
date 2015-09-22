<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneSalesConnector\Business;

use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\PayoneSalesConnector\Dependency\Facade\PayoneSalesConnectorToPayoneInterface;

class PayoneSalesConnector
{

    /**
     * @var PayoneSalesConnectorToPayoneInterface
     */
    protected $payoneFacade;

    /**
     * @param PayoneSalesConnectorToPayoneInterface $payoneFacade
     */
    public function __construct(PayoneSalesConnectorToPayoneInterface $payoneFacade)
    {
        $this->payoneFacade = $payoneFacade;
    }

    /**
     * @param ObjectCollection $orders
     *
     * @return PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        $this->payoneFacade->getPaymentLogs($orders);
    }

}
