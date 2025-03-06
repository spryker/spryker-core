<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\Mail;

use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\MailTemplateTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MailExtension\Dependency\Plugin\MailTypeBuilderPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 */
class SspInquiryApprovedMailTypeBuilderPlugin extends AbstractPlugin implements MailTypeBuilderPluginInterface
{
    /**
     * @var string
     */
    protected const ROUTE_NAME_SSP_INQUIRY_LIST = 'customer/ssp-inquiry';

    /**
     * @var string
     */
    protected const MAIL_TYPE = 'ssp inquiry approved';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_HTML = 'SspInquiryManagement/Mail/ssp-inquiry_approved.html.twig';

    /**
     * @var string
     */
    protected const MAIL_TEMPLATE_TEXT = 'SspInquiryManagement/Mail/ssp-inquiry_approved.text.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MAIL_SUBJECT = 'ssp_inquiry.mail.trans.ssp_inquiry_approved.subject';

    /**
     * {@inheritDoc}
     * - Returns the name of ssp inquiry approval mail.
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
     * - Builds the `MailTransfer` with data for the approved ssp inquiry mail.
     * - Requires `MailTransfer.sspInquiry.companyUser.customer.email` to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    public function build(MailTransfer $mailTransfer): MailTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
         */
         $sspInquiryTransfer = $mailTransfer->getSspInquiry();

        /**
         * @var \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
         */
        $companyUserTransfer = $sspInquiryTransfer->getCompanyUser();

        /**
         * @var \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
         */
        $customerTransfer = $companyUserTransfer->getCustomer();

        $mailTransfer->setSspInquiryUrl(
            $this->getConfig()->getYvesBaseUrl() . '/' . static::ROUTE_NAME_SSP_INQUIRY_LIST,
        );

        return $mailTransfer
            ->setSubject(static::GLOSSARY_KEY_MAIL_SUBJECT)
            ->setSubjectTranslationParameters(
                ['%reference%' => (string)$sspInquiryTransfer->getReference()],
            )
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
                    ->setEmail($customerTransfer->getEmail()),
            );
    }
}
