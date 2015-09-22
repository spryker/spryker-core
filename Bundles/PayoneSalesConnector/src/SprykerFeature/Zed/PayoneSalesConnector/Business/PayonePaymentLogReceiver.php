<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\PayoneSalesConnector\Business;

use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\PayoneSalesConnector\Dependency\Facade\PayoneSalesConnectorToPayoneInterface;

class PayonePaymentLogReceiver
{

    /**
     * @var PayoneSalesConnectorToPayoneInterface
     */
    private $payoneFacade;

    public function __construct(PayoneSalesConnectorToPayoneInterface $payoneSalesConnectorToPayoneInterface)
    {
        $this->payoneFacade = $payoneSalesConnectorToPayoneInterface;
    }

    /**
     * @param ObjectCollection $orders
     *
     * @return PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        return $this->payoneFacade->getPaymentLogs($orders);
    }

}
