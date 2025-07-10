<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 */
class NotifyOrderAmendmentAppliedMailTypeBuilderPlugin extends AbstractPlugin implements MailTypeBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const MAIL_TYPE = 'notify order amendment applied';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_HTML = 'sales-order-amendment-oms/Mail/notify-order-amendment-applied.html.twig';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_TEXT = 'sales-order-amendment-oms/Mail/notify-order-amendment-applied.text.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MAIL_SUBJECT = 'sales_order_amendment_oms.mail.order_amendment_applied.subject';

    /**
     * {@inheritDoc}
     * - Returns the name of mail for `notify order amendment applied` mail.
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
     * - Requires `MailTransfer.quote` to be set.
     * - Requires `MailTransfer.customer` to be set.
     * - Requires `MailTransfer.customer.email` to be set.
     * - Builds the `MailTransfer` with data for `notify order amendment applied` mail.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer
    {
        $quoteTransfer = $mailTransfer->getQuoteOrFail();

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
                    ->setEmail($quoteTransfer->getCustomerOrFail()->getEmailOrFail()),
            );
    }
}
