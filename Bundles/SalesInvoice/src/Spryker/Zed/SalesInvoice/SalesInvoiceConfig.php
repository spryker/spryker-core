<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\SalesInvoice\Business\Exception\OrderInvoiceTemplatePathNotConfiguredException;

/**
 * @method \Spryker\Shared\SalesInvoice\SalesInvoiceConfig getSharedConfig()
 */
class SalesInvoiceConfig extends AbstractBundleConfig
{
    public const ORDER_INVOICE_MAIL_TYPE = 'order invoice';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getOrderInvoiceReferenceSequence(): SequenceNumberSettingsTransfer
    {
        return (new SequenceNumberSettingsTransfer())
            ->setName($this->getSharedConfig()->getOrderInvoiceSequence())
            ->setPrefix(
                $this->getOrderInvoiceReferencePrefix()
            );
    }

    /**
     * @return string
     */
    protected function getOrderInvoiceReferencePrefix(): string
    {
        return $this->getSharedConfig()->getOrderInvoiceSequence() . '-';
    }

    /**
     * Specification:
     * - Retrieves the template path that will be permanently used for the currently generated order invoices.
     *
     * @api
     *
     * @throws \Spryker\Zed\SalesInvoice\Business\Exception\OrderInvoiceTemplatePathNotConfiguredException
     *
     * @return string
     */
    public function getOrderInvoiceTemplatePath(): string
    {
        throw new OrderInvoiceTemplatePathNotConfiguredException(
            'You need to provide an invoice template!'
        );
    }

    /**
     * Specification:
     * - Retrieves the BCC that will be added to all sent order invoice email.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\MailRecipientTransfer[]
     */
    public function getOrderInvoiceBcc(): array
    {
        return [];
    }
}
