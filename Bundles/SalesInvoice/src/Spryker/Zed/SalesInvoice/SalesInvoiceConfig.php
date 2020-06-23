<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesInvoice;

use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\SalesInvoice\SalesInvoiceConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\SalesInvoice\Business\Exception\OrderInvoiceTemplatePathNotConfiguredException;

class SalesInvoiceConfig extends AbstractBundleConfig
{
    public const ORDER_INVOICE_MAIL_TYPE = 'order invoice';

    protected const ORDER_INVOICE_PREFIX_DEFAULT = 'Invoice';
    protected const NAME_ORDER_INVOICE_REFERENCE = 'InvoiceReference';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getOrderInvoiceReferenceDefaults(): SequenceNumberSettingsTransfer
    {
        $sequenceNumberSettingsTransfer = (new SequenceNumberSettingsTransfer())
            ->setName(static::NAME_ORDER_INVOICE_REFERENCE);

        $prefix = $this->getOrderInvoicePrefix() . $this->getUniqueIdentifierSeparator();
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
    }

    /**
     * @return string
     */
    protected function getOrderInvoicePrefix(): string
    {
        return (string)$this->get(SalesInvoiceConstants::ORDER_INVOICE_PREFIX, static::ORDER_INVOICE_PREFIX_DEFAULT);
    }

    /**
     * @return string
     */
    protected function getUniqueIdentifierSeparator(): string
    {
        return '-';
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
     * - Retrieves the BCC that will be added to all sent order invoice email
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
