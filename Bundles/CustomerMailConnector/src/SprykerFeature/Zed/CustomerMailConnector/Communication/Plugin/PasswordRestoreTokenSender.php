<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorConfig;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class PasswordRestoreTokenSender extends AbstractSender implements PasswordRestoreTokenSenderPluginInterface
{

    /**
     * @param string $email
     * @param string $token
     *
     * @return bool
     */
    public function send($email, $token)
    {
        $config = $this->getDependencyContainer()->getConfig();

        $mailTransfer = $this->createMailTransfer();

        $mailTransfer->setTemplateName($config->getPasswordRestoreToken());

        $this->addMailRecipient($mailTransfer, $email);
        $this->setMailTransferFrom($mailTransfer, $config);
        $this->setMailTransferSubject($mailTransfer, $config);
        $this->setMailMergeData($mailTransfer, $this->getMailGlobalMergeVars($token));

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
        $mailTransfer->setSubject($this->translate($config->getPasswordRestoreSubject()));
    }

    /**
     * @param string $token
     *
     * @return array
     */
    protected function getMailGlobalMergeVars($token)
    {
        $globalMergeVars = [
            'reset_password_token_url' => $token,
        ];

        return $globalMergeVars;
    }

}
