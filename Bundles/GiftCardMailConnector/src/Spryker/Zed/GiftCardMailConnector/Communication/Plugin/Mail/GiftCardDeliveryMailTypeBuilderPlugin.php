<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface;

/**
 * @method \Spryker\Zed\GiftCardMailConnector\GiftCardMailConnectorConfig getConfig()
 * @method \Spryker\Zed\GiftCardMailConnector\Communication\GiftCardMailConnectorCommunicationFactory getFactory()
 * @method \Spryker\Zed\GiftCardMailConnector\Business\GiftCardMailConnectorFacadeInterface getFacade()
 */
class GiftCardDeliveryMailTypeBuilderPlugin extends AbstractPlugin implements MailTypeBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const MAIL_TYPE = 'GIFT_CARD_DELIVERY_MAIL';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_HTML = 'giftCardMailConnector/mail/gift_card_delivery.html.twig';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_TEXT = 'giftCardMailConnector/mail/gift_card_delivery.text.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MAIL_SUBJECT = 'mail.giftCard.delivery.subject';

    /**
     * {@inheritDoc}
     * - Returns the name of mail for a gift card delivery mail.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::MAIL_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Builds the `MailTransfer` with data for a gift card delivery mail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer
    {
        /** @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer */
        $customerTransfer = $mailTransfer->getCustomerOrFail();

        return $mailTransfer
            ->setSubject(static::GLOSSARY_KEY_MAIL_SUBJECT)
            ->addTemplate(
                (new MailTemplateTransfer())
                    ->setName(static::MAIL_TEMPLATE_HTML)
                    ->setIsHtml(true),
            )
            ->addTemplate(
                (new MailTemplateTransfer())
                    ->setName(static::MAIL_TEMPLATE_TEXT)
                    ->setIsHtml(false),
            )
            ->addRecipient(
                (new MailRecipientTransfer())
                    ->setEmail($customerTransfer->getEmailOrFail())
                    ->setName(sprintf('%s %s', $customerTransfer->getFirstName(), $customerTransfer->getLastName())),
            );
    }
}
