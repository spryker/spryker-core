<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\PayoneSalesConnector\Business;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\PayoneSalesConnector\Dependency\Facade\PayoneSalesConnectorToPayoneInterface;

class PayonePaymentLogReceiver {

    /**
     * @var PayoneSalesConnectorToPayoneInterface
     */
    private $payoneFacade;

    public function __construct(PayoneSalesConnectorToPayoneInterface $payoneSalesConnectorToPayoneInterface) {
        $this->payoneFacade = $payoneSalesConnectorToPayoneInterface;
    }

    public function getPaymentLogs(ObjectCollection $orders) {
        return $this->payoneFacade->getPaymentLogs($orders);
    }

}