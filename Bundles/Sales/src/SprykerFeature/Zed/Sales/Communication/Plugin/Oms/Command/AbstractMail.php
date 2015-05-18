<?php
namespace SprykerFeature\Zed\Sales\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\Mail\Business\Model\Adapter\DatabaseQueueMailAdapter;
use SprykerFeature\Zed\Mail\Business\Model\Adapter\DirectMailAdapter;
use SprykerFeature\Zed\Mail\Business\Model\Provider\MailProviderResponse;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\AbstractCommand;

abstract class AbstractMail extends AbstractCommand
{

    /**
     * @param MailProviderResponse $mailProviderResponse
     * @param Mail $mailTransfer
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity
     */
    protected function handleResponse(MailProviderResponse $mailProviderResponse, Mail $mailTransfer, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $orderEntity)
    {
        if ($mailProviderResponse !== false) {
            switch($mailProviderResponse->getUsedAdapterName()) {
                case DatabaseQueueMailAdapter::getName():
                    $this->addNote('Queued "' . $mailTransfer->getType() . '"-mail, will be sent asynchronously by mail-queue later: ' . $mailTransfer->getRecipientAddress() . ' | ' . $mailProviderResponse->getMessage(), $orderEntity, true);
                    break;
                case DirectMailAdapter::getName():
                    $this->addNote('Sended "' . $mailTransfer->getType() . '"-mail to customer directly (no queueing): ' . $mailTransfer->getRecipientAddress(). ' | ' . $mailProviderResponse->getMessage(), $orderEntity, true);
                    break;
            }
        } else {
            $this->addNote('Error! No "' . $mailTransfer->getType() . '"-mail sended to customer: ' . $mailTransfer->getRecipientAddress(). ' | ' . $mailProviderResponse->getMessage(), $orderEntity, false);
        }
    }
}
