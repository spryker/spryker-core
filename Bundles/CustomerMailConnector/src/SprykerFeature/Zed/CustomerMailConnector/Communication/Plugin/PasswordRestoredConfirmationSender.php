<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorConfig;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class PasswordRestoredConfirmationSender extends AbstractSender implements PasswordRestoredConfirmationSenderPluginInterface
{

    /**
     * @param string $email
     *
     * @return bool
     */
    public function send($email)
    {
        $config = $this->getDependencyContainer()->getConfig();

        $mailTransfer = $this->createMailTransfer();

        $mailTransfer->setTemplateName($config->getPasswordRestoredConfirmationToken());

        $this->addMailRecipient($mailTransfer, $email);
        $this->setMailTransferFrom($mailTransfer, $config);
        $this->setMailTransferSubject($mailTransfer, $config);
        $this->setMailMergeData($mailTransfer);

        $result = $this->getDependencyContainer()
            ->createMailFacade()
            ->sendMail($mailTransfer);

        return $this->isMailSent($result);
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param CustomerMailConnectorConfig $config
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer, CustomerMailConnectorConfig $config)
    {
        $mailTransfer->setSubject($this->translate($config->getPasswordRestoredConfirmationSubject()));
    }

}
