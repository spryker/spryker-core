<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspInquiryRejectionHandler implements SspInquiryRejectionHandlerInterface
{
    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Mail\SspInquiryRejectedMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'ssp inquiry rejected';

    public function __construct(
        protected SspInquiryReaderInterface $sspInquiryReader,
        protected MailFacadeInterface $mailFacade,
        protected CustomerFacadeInterface $customerFacade,
        protected SelfServicePortalConfig $config
    ) {
    }

    public function handleRejection(StateMachineItemTransfer $stateMachineItemTransfer): void
    {
        $sspInquiryCriteriaTransfer = (new SspInquiryCriteriaTransfer())
            ->setInclude(
                (new SspInquiryIncludeTransfer())
                    ->setWithCompanyUser(true),
            )
            ->setSspInquiryConditions(
                (new SspInquiryConditionsTransfer())
                    ->setSspInquiryIds([(int)$stateMachineItemTransfer->getIdentifier()]),
            );

        $sspInquiryCollectionTransfer = $this->sspInquiryReader->getSspInquiryCollection($sspInquiryCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer */
        $sspInquiryTransfer = $sspInquiryCollectionTransfer->getSspInquiries()->offsetGet(0);

        $this->mailFacade->handleMail(
            (new MailTransfer())
                ->setSspInquiry($sspInquiryTransfer)
                ->setCustomer(
                    $this->customerFacade->getCustomer((new CustomerTransfer())->setIdCustomer(
                        $sspInquiryTransfer->getCompanyUserOrFail()->getCustomerOrFail()->getIdCustomerOrFail(),
                    )),
                )
                ->setType(static::MAIL_TYPE)
                ->setSspInquiryUrl(
                    $this->config->getYvesBaseUrl(),
                ),
        );
    }
}
