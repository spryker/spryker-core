<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneSalesConnector\Business;

use Generated\Shared\Sales\OrderInterface;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\PayoneSalesConnector\Dependency\Facade\PayoneSalesConnectorToPayoneInterface;

class PayoneSalesConnectorToPayone
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
     * @return array
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
         $this->payoneFacade->getPaymentLogs($orders);
    }

}
