<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorConfig;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class RegistrationTokenSender extends AbstractSender implements RegistrationTokenSenderPluginInterface
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

        $mailTransfer->setTemplateName($config->getRegistrationToken());

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
        $mailTransfer->setSubject($this->translate($config->getRegistrationSubject()));
    }

    /**
     * @param string $token
     *
     * @return array
     */
    protected function getMailGlobalMergeVars($token)
    {
        $globalMergeVars = [
            'registration_token_url' => $token,
        ];

        return $globalMergeVars;
    }

}
