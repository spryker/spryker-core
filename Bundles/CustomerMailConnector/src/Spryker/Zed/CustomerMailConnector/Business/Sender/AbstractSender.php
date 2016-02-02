<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CustomerMailConnector\Business\Sender;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\CustomerMailConnector\CustomerMailConnectorConfig;
use Spryker\Zed\CustomerMailConnector\Dependency\Facade\CustomerMailConnectorToGlossaryInterface;
use Spryker\Zed\CustomerMailConnector\Dependency\Facade\CustomerMailConnectorToMailInterface;

abstract class AbstractSender
{

    /**
     * @var CustomerMailConnectorConfig
     */
    protected $config;

    /**
     * @var CustomerMailConnectorToMailInterface
     */
    protected $mailFacade;

    /**
     * @var CustomerMailConnectorToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\CustomerMailConnector\CustomerMailConnectorConfig $config
     * @param \Spryker\Zed\CustomerMailConnector\Dependency\Facade\CustomerMailConnectorToMailInterface $mailFacade
     * @param \Spryker\Zed\CustomerMailConnector\Dependency\Facade\CustomerMailConnectorToGlossaryInterface $glossaryFacade
     */
    public function __construct(
        CustomerMailConnectorConfig $config,
        CustomerMailConnectorToMailInterface $mailFacade,
        CustomerMailConnectorToGlossaryInterface $glossaryFacade
    ) {
        $this->config = $config;
        $this->mailFacade = $mailFacade;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function createMailTransfer()
    {
        return new MailTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param string $email
     *
     * @return void
     */
    protected function addMailRecipient(MailTransfer $mailTransfer, $email)
    {
        $mailRecipientTransfer = $this->createMailRecipientTransfer();
        $mailRecipientTransfer->setEmail($email);
        $mailTransfer->addRecipient($mailRecipientTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\MailRecipientTransfer
     */
    protected function createMailRecipientTransfer()
    {
        return new MailRecipientTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param array $globalMergeVars
     *
     * @return void
     */
    protected function setMailMergeData(MailTransfer $mailTransfer, array $globalMergeVars = [])
    {
        $mailTransfer->setMerge(true);
        $mailTransfer->setMergeLanguage($this->config->getMergeLanguage());
        $mailTransfer->setGlobalMergeVars($globalMergeVars);
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    protected function setMailTransferFrom(MailTransfer $mailTransfer)
    {
        $fromName = $this->config->getFromEmailName();
        if ($fromName !== null) {
            $mailTransfer->setFromName($fromName);
        }

        $fromEmail = $this->config->getFromEmailAddress();
        if ($fromEmail !== null) {
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
