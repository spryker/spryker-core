<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice\Communication\Plugin\Mail;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface;
use Spryker\Zed\Mail\Dependency\Plugin\MailTypePluginInterface;
use Spryker\Zed\SalesInvoice\SalesInvoiceConfig;

/**
 * @method \Spryker\Zed\SalesInvoice\SalesInvoiceConfig getConfig()
 * @method \Spryker\Zed\SalesInvoice\Business\SalesInvoiceFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesInvoice\Communication\SalesInvoiceCommunicationFactory getFactory()
 */
class OrderInvoiceMailTypePlugin extends AbstractPlugin implements MailTypePluginInterface
{
    protected const GLOSSARY_KEY_MAIL_ORDER_INVOICE_SUBJECT = 'mail.order_invoice.subject';
    protected const GLOSSARY_KEY_MAIL_SENDER_EMAIL = 'mail.sender.email';
    protected const GLOSSARY_KEY_MAIL_SENDER_NAME = 'mail.sender.name';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return SalesInvoiceConfig::ORDER_INVOICE_MAIL_TYPE;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return void
     */
    public function build(MailBuilderInterface $mailBuilder): void
    {
        $this
            ->setSubject($mailBuilder)
            ->setHtmlTemplate($mailBuilder)
            ->setTextTemplate($mailBuilder)
            ->setRecipient($mailBuilder)
            ->setRecipientBccs($mailBuilder)
            ->setSender($mailBuilder);
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setSubject(MailBuilderInterface $mailBuilder)
    {
        $invoiceReference = $mailBuilder->getMailTransfer()->getOrderInvoice()->getReference();
        $mailBuilder->setSubject(static::GLOSSARY_KEY_MAIL_ORDER_INVOICE_SUBJECT, [
            '%invoiceReference%' => $invoiceReference,
        ]);

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setHtmlTemplate(MailBuilderInterface $mailBuilder)
    {
        $mailBuilder->setHtmlTemplate('salesInvoice/mail/order_invoice.html.twig');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setTextTemplate(MailBuilderInterface $mailBuilder)
    {
        $mailBuilder->setTextTemplate('salesInvoice/mail/order_invoice.text.twig');

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setRecipient(MailBuilderInterface $mailBuilder)
    {
        $orderTransfer = $this->getOrderTransfer($mailBuilder);

        $mailBuilder->addRecipient(
            $orderTransfer->getEmail(),
            $orderTransfer->getFirstName() . ' ' . $orderTransfer->getLastName()
        );

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setRecipientBccs(MailBuilderInterface $mailBuilder)
    {
        foreach ($this->getConfig()->getOrderInvoiceBcc() as $recipientTransfer) {
            $mailBuilder->addRecipientBcc($recipientTransfer->getEmail(), $recipientTransfer->getName());
        }

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return $this
     */
    protected function setSender(MailBuilderInterface $mailBuilder)
    {
        $mailBuilder->useDefaultSender();

        return $this;
    }

    /**
     * @param \Spryker\Zed\Mail\Business\Model\Mail\Builder\MailBuilderInterface $mailBuilder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function getOrderTransfer(MailBuilderInterface $mailBuilder): OrderTransfer
    {
        return $mailBuilder->getMailTransfer()
            ->requireOrder()
            ->getOrder();
    }
}
