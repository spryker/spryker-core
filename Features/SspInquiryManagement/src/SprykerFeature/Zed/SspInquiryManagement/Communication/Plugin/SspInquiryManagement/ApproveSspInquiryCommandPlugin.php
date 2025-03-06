<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\SspInquiryManagement;

use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class ApproveSspInquiryCommandPlugin extends AbstractPlugin implements CommandPluginInterface
{
    /**
     * @uses \SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\Mail\SspInquiryApprovedMailTypeBuilderPlugin::MAIL_TYPE
     *
     * @var string
     */
    protected const MAIL_TYPE = 'ssp inquiry approved';

    /**
     * {@inheritDoc}
     * - Called when event have specific command assigned.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function run(StateMachineItemTransfer $stateMachineItemTransfer): void
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

         $sspInquiryCollectionTransfer = $this->getFacade()->getSspInquiryCollection($sspInquiryCriteriaTransfer);

        /** @var \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer */
         $sspInquiryTransfer = $sspInquiryCollectionTransfer->getSspInquiries()->offsetGet(0);

        $this->getFactory()->getMailFacade()->handleMail(
            (new MailTransfer())
                ->setSspInquiry($sspInquiryTransfer)
                ->setCustomer(
                    $this->getRepository()->getCustomerByIdCompanyUser($sspInquiryTransfer->getCompanyUserOrFail()->getIdCompanyUserOrFail()),
                )
                ->setType(static::MAIL_TYPE)
                ->setSspInquiryUrl(
                    $this->getConfig()->getYvesBaseUrl(),
                ),
        );
    }
}
