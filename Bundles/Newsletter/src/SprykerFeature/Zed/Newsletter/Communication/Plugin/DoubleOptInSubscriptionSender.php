<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Communication\Plugin;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Mail\MailConfig;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriberOptInSenderInterface;
use SprykerFeature\Zed\Newsletter\Communication\NewsletterDependencyContainer;
use SprykerFeature\Zed\Newsletter\NewsletterConfig;

/**
 * @method NewsletterDependencyContainer getDependencyContainer()
 */
class DoubleOptInSubscriptionSender extends AbstractPlugin implements SubscriberOptInSenderInterface
{

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return bool
     */
    public function send(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        $config = $this->getDependencyContainer()->getConfig();

        $mailTransfer = $this->createMailTransfer();

        $mailTransfer->setTemplateName($config->getDoubleOptInConfirmationTemplateName());

        $this->addMailRecipient($mailTransfer, $newsletterSubscriber->getEmail());
        $this->setMailTransferFrom($mailTransfer, $config);
        $this->setMailTransferSubject($mailTransfer, $config);

        $globalMergeVars = $this->getMailGlobalMergeVars(
            $config->getDoubleOptInApproveTokenUrl($newsletterSubscriber->getSubscriberKey())
        );
        $this->setMailMergeData($mailTransfer, $globalMergeVars);

        $mailFacade = $this->getDependencyContainer()->getMailFacade();
        $responses = $mailFacade->sendMail($mailTransfer);
        $result = $mailFacade->isMailSent($responses);

        return $result;
    }

    /**
     * @param MailTransfer $mailTransfer
     * @param NewsletterConfig $config
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
     * @param NewsletterConfig $config
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
        $glossaryFacade = $this->getDependencyContainer()->getGlossaryFacade();
        if ($glossaryFacade->hasTranslation($keyName)) {
            return $glossaryFacade->translate($keyName);
        }

        return $keyName;
    }

}
