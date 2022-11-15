<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface;

/**
 * @method \Spryker\Zed\SalesInvoice\SalesInvoiceConfig getConfig()
 * @method \Spryker\Zed\SalesInvoice\Communication\SalesInvoiceCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacadeInterface getFacade()
 */
class OrderInvoiceMailTypeBuilderPlugin extends AbstractPlugin implements MailTypeBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const MAIL_TYPE = 'order invoice';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_HTML = 'salesInvoice/mail/order_invoice.html.twig';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_TEXT = 'salesInvoice/mail/order_invoice.text.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MAIL_SUBJECT = 'mail.order_invoice.subject';

    /**
     * @var string
     */
    protected const PARAMETER_INVOICE_REFERENCE = '%invoiceReference%';

    /**
     * {@inheritDoc}
     * - Returns the name of mail for an order invoice mail.
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
     * - Builds the `MailTransfer` with data for an order invoice mail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer
    {
        /** @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer */
        $orderTransfer = $mailTransfer->getOrderOrFail();
        $mailTransfer = $this->setRecipientBccs($mailTransfer);

        return $mailTransfer = $mailTransfer
            ->setSubject(static::GLOSSARY_KEY_MAIL_SUBJECT)
            ->setSubjectTranslationParameters([static::PARAMETER_INVOICE_REFERENCE => $mailTransfer->getOrderInvoiceOrFail()->getReferenceOrFail()])
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
                    ->setEmail($orderTransfer->getEmailOrFail())
                    ->setName(sprintf('%s %s', $orderTransfer->getFirstName(), $orderTransfer->getLastName())),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function setRecipientBccs(MailTransfer $mailTransfer): MailTransfer
    {
        foreach ($this->getConfig()->getOrderInvoiceBcc() as $recipientTransfer) {
            $mailTransfer->addRecipientBcc(
                (new MailRecipientTransfer())
                    ->setName($recipientTransfer->getName())
                    ->setEmail($recipientTransfer->getEmailOrFail()),
            );
        }

        return $mailTransfer;
    }
}
