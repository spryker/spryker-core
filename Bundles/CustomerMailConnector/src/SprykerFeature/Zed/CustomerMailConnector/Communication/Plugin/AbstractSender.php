<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Mail\MailConfig;
use SprykerFeature\Zed\CustomerMailConnector\Communication\CustomerMailConnectorDependencyContainer;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorConfig;

/**
 * @method CustomerMailConnectorDependencyContainer getDependencyContainer()
 */
class AbstractSender extends AbstractPlugin
{

    /**
     * @param array $results
     *
     * @return bool
     */
    protected function isMailSent(array $results)
    {
        foreach ($results as $result) {
            if (!isset($result['status'])) {
                return false;
            }
            if ($result['status'] !== 'sent' || $result['status'] !== 'queued') {
                return false;
            }
        }

        return true;
    }

    /**
     * @return MailTransfer
     */
    protected function createMailTransfer()
    {
        return new MailTransfer();
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param string $email
     */
    protected function addMailRecipient(MailTransfer $mailTransfer, $email)
    {
        $mailRecipientTransfer = $this->createMailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);
        $mailTransfer->addRecipient($mailRecipientTransfer);
    }

    /**
     * @return MailRecipientTransfer
     */
    protected function createMailRecipientTransfer()
    {
        return new MailRecipientTransfer();
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param array $globalMergeVars
     */
    protected function setMailMergeData(MailTransfer $mailTransfer, array $globalMergeVars = [])
    {
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage($this->getMergeLanguage());
        $mailTransfer->setGlobalMergeVars($globalMergeVars);
    }

    /**
     * @return string
     */
    protected function getMergeLanguage()
    {
        return MailConfig::MERGE_LANGUAGE_HANDLEBARS;
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param CustomerMailConnectorConfig $config
     */
    protected function setMailTransferFrom(MailTransfer $mailTransfer, CustomerMailConnectorConfig $config)
    {
        $fromName = $config->getFromEmailName();
        if (null !== $fromName) {
            $mailTransfer->setFromName($fromName);
        }

        $fromEmail = $config->getFromEmailAddress();
        if (null !== $fromEmail) {
            $mailTransfer->setFromEmail($fromEmail);
        }
    }

    /**
     * @param string $keyName
     *
     * @return string
     */
    protected function translate($keyName)
    {
        $glossaryFacade = $this->getDependencyContainer()->createGlossaryFacade();
        if ($glossaryFacade->hasTranslation($keyName)) {
            return $glossaryFacade->translate($keyName);
        }

        return $keyName;
    }

}
