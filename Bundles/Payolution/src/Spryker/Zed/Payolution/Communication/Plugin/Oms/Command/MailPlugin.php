<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Communication\Plugin\Oms\Command;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Dependency\Plugin\Command\CommandByOrderInterface;
use Spryker\Zed\Payolution\PayolutionConfig;

/**
 * @method \Spryker\Zed\Payolution\Business\PayolutionFacade getFacade()
 * @method \Spryker\Zed\Payolution\Communication\PayolutionCommunicationFactory getFactory()
 */
class MailPlugin extends AbstractPlugin implements CommandByOrderInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     * @param \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject $data
     *
     * @return array
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $config = $this->getFactory()->getConfig();
        $mailTransfer = new MailTransfer();

        $mailTransfer->setTemplateName($config->getEmailTemplateName());
        $this->addMailRecipient($mailTransfer, $orderEntity->getEmail());
        $this->addMailRecipient($mailTransfer, $this->getPayolutionBccEmail($config));
        $this->setMailTransferFrom($mailTransfer, $config);
        $this->setMailTransferSubject($mailTransfer, $config);

        $mailFacade = $this->getFactory()->getMailFacade();
        $mailFacade->sendMail($mailTransfer);

        return [];
    }

    /**
     * @param \Spryker\Zed\Payolution\PayolutionConfig $config
     *
     * @return string
     */
    protected function getPayolutionBccEmail(PayolutionConfig $config)
    {
        return $config->getPayolutionBccEmail();
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param string $email
     *
     * @return void
     */
    protected function addMailRecipient(MailTransfer $mailTransfer, $email)
    {
        $mailRecipientTransfer = new MailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);
        $mailTransfer->addRecipient($mailRecipientTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Spryker\Zed\Payolution\PayolutionConfig $config
     *
     * @return void
     */
    protected function setMailTransferFrom(MailTransfer $mailTransfer, PayolutionConfig $config)
    {
        $fromName = $config->getEmailFromName();
        if ($fromName !== null) {
            $mailTransfer->setFromName($fromName);
        }

        $fromEmail = $config->getEmailFromAddress();
        if ($fromEmail !== null) {
            $mailTransfer->setFromEmail($fromEmail);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Spryker\Zed\Payolution\PayolutionConfig $config
     *
     * @return void
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer, PayolutionConfig $config)
    {
        $subject = $config->getEmailSubject();
        if ($subject !== null) {
            $mailTransfer->setSubject($this->translate($subject));
        }
    }

    /**
     * @param string $keyName
     *
     * @return string
     */
    protected function translate($keyName)
    {
        $glossaryFacade = $this->getFactory()->getGlossaryFacade();

        if ($glossaryFacade->hasTranslation($keyName)) {
            return $glossaryFacade->translate($keyName);
        }

        return $keyName;
    }
}
