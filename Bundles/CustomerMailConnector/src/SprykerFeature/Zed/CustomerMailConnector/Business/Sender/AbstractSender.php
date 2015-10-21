<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerMailConnector\Business\Sender;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerFeature\Zed\CustomerMailConnector\CustomerMailConnectorConfig;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Mail\Business\MailFacade;

abstract class AbstractSender
{

    /**
     * @var CustomerMailConnectorConfig
     */
    protected $config;

    /**
     * @var MailFacade
     */
    protected $mailFacade;

    /**
     * @var GlossaryFacade
     */
    protected $glossaryFacade;

    /**
     * @param CustomerMailConnectorConfig $config
     * @param MailFacade $mailFacade
     * @param GlossaryFacade $glossaryFacade
     */
    public function __construct(CustomerMailConnectorConfig $config, MailFacade $mailFacade, GlossaryFacade $glossaryFacade)
    {
        $this->config = $config;
        $this->mailFacade = $mailFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

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
        $mailTransfer->setMergeLanguage($this->config->getMergeLanguage());
        $mailTransfer->setGlobalMergeVars($globalMergeVars);
    }

    /**
     * @param MailTransfer $mailTransfer
     */
    protected function setMailTransferFrom(MailTransfer $mailTransfer)
    {
        $fromName = $this->config->getFromEmailName();
        if (null !== $fromName) {
            $mailTransfer->setFromName($fromName);
        }

        $fromEmail = $this->config->getFromEmailAddress();
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
        if ($this->glossaryFacade->hasTranslation($keyName)) {
            return $this->glossaryFacade->translate($keyName);
        }

        return $keyName;
    }

}
