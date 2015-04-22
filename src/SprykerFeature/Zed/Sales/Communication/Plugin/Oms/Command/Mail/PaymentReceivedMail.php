<?php

namespace SprykerFeature\Zed\Sales\Business\Model\Orderprocess\Command\Mail;

use SprykerFeature\Zed\Oms\Business\Model\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Mail\Business\Model\MailTypesConstantInterface;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command\AbstractMail;

class PaymentReceivedMail extends AbstractMail implements
    CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     * @return array|void
     */
    public function run(array $orderItems, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $mailTransfer = $this->facadeMail->buildOrderMailTransfer(
            MailTypesConstantInterface::PAYMENT_RECEIVED,
            $orderItems
        );
        $result = $this->facadeMail->sendMail($mailTransfer);
        $this->handleResponse($result, $mailTransfer, $orderEntity);
    }
}
