<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Newsletter\Business\Subscription\SubscriberOptInSenderInterface;
use Spryker\Zed\Newsletter\NewsletterConfig;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Zed\Newsletter\Communication\NewsletterCommunicationFactory getFactory()
 * @method \Spryker\Zed\Newsletter\Business\NewsletterFacadeInterface getFacade()
 * @method \Spryker\Zed\Newsletter\NewsletterConfig getConfig()
 * @method \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainerInterface getQueryContainer()
 */
class DoubleOptInSubscriptionSender extends AbstractPlugin implements SubscriberOptInSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return bool
     */
    public function send(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        $config = $this->getFactory()->getConfig();

        $mailTransfer = $this->createMailTransfer();

        $mailTransfer->setTemplateName($config->getDoubleOptInConfirmationTemplateName());

        $this->addMailRecipient($mailTransfer, $newsletterSubscriber->getEmail());
        $this->setMailTransferFrom($mailTransfer, $config);
        $this->setMailTransferSubject($mailTransfer, $config);

        $globalMergeVars = $this->getMailGlobalMergeVars(
            $config->getDoubleOptInApproveTokenUrl($newsletterSubscriber->getSubscriberKey())
        );
        $this->setMailMergeData($mailTransfer, $globalMergeVars);

        $mailFacade = $this->getFactory()->getMailFacade();
        $responses = $mailFacade->sendMail($mailTransfer);
        $result = $mailFacade->isMailSent($responses);

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Spryker\Zed\Newsletter\NewsletterConfig $config
     *
     * @return void
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer, NewsletterConfig $config)
    {
        $subject = $config->getPasswordRestoreSubject();
        if ($subject !== null) {
            $mailTransfer->setSubject($this->translate($subject));
        }
    }

    /**
     * @param string $token
     *
     * @return array
     */
    protected function getMailGlobalMergeVars($token)
    {
        $globalMergeVars = [
            'subscription_approval_token_url' => $token,
        ];

        return $globalMergeVars;
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
        $mailTransfer->setMergeLanguage($this->getMergeLanguage());
        $mailTransfer->setGlobalMergeVars($globalMergeVars);
    }

    /**
     * @return string
     */
    protected function getMergeLanguage()
    {
        return $this->getFactory()->getConfig()->getMergeLanguage();
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Spryker\Zed\Newsletter\NewsletterConfig $config
     *
     * @return void
     */
    protected function setMailTransferFrom(MailTransfer $mailTransfer, NewsletterConfig $config)
    {
        $fromName = $config->getFromEmailName();
        if ($fromName !== null) {
            $mailTransfer->setFromName($fromName);
        }

        $fromEmail = $config->getFromEmailAddress();
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
        $glossaryFacade = $this->getFactory()->getGlossaryFacade();
        if ($glossaryFacade->hasTranslation($keyName)) {
            return $glossaryFacade->translate($keyName);
        }

        return $keyName;
    }
}
