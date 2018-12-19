<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionSenderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Communication\AvailabilityNotificationCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationQueryContainerInterface getQueryContainer()
 */
class AvailabilityNotificationSubscriptionSender extends AbstractPlugin implements AvailabilityNotificationSubscriptionSenderInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return bool
     */
    public function send(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer)
    {
        $config = $this->getFactory()->getConfig();

        $mailTransfer = $this->createMailTransfer();

        $mailTransfer->setTemplateName($config->getDoubleOptInConfirmationTemplateName());

        $this->addMailRecipient($mailTransfer, $availabilityNotificationSubscriptionTransfer->getEmail());
        $this->setMailTransferFrom($mailTransfer, $config);
        $this->setMailTransferSubject($mailTransfer, $config);

        $globalMergeVars = $this->getMailGlobalMergeVars(
            $config->getDoubleOptInApproveTokenUrl($availabilityNotificationSubscriptionTransfer->getSubscriptionKey())
        );
        $this->setMailMergeData($mailTransfer, $globalMergeVars);

        $mailFacade = $this->getFactory()->getMailFacade();
        $responses = $mailFacade->sendMail($mailTransfer);
        $result = $mailFacade->isMailSent($responses);

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $config
     *
     * @return void
     */
    protected function setMailTransferSubject(MailTransfer $mailTransfer, AvailabilityNotificationConfig $config)
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
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $config
     *
     * @return void
     */
    protected function setMailTransferFrom(MailTransfer $mailTransfer, AvailabilityNotificationConfig $config)
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
