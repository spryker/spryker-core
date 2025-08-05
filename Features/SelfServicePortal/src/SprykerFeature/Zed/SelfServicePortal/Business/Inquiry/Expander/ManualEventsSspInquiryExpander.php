<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class ManualEventsSspInquiryExpander implements SspInquiryExpanderInterface
{
    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected StateMachineFacadeInterface $stateMachineFacade
    ) {
    }

    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        $stateMachineItemTransfers = [];

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $stateMachineItemTransfer = new StateMachineItemTransfer();
            $stateMachineItemTransfer->setIdentifier($sspInquiryTransfer->getIdSspInquiry())
                ->setStateMachineName($this->selfServicePortalConfig->getInquiryStateMachineName())
                ->setProcessName($this->selfServicePortalConfig->getSspInquiryStateMachineProcessInquiryTypeMap()[$sspInquiryTransfer->getType()])
                ->setStateName($sspInquiryTransfer->getStatus());

            $stateMachineItemTransfers[] = $stateMachineItemTransfer;
        }

        $manualEventsPerIdentifier = $this->stateMachineFacade->getManualEventsForStateMachineItems(
            $stateMachineItemTransfers,
        );

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
             $sspInquiryTransfer
                ->setManualEvents($manualEventsPerIdentifier[$sspInquiryTransfer->getIdSspInquiry()] ?? [])
                ->setIsCancellable(
                    $this->selfServicePortalConfig->getSspInquiryCancelStateMachineEventName()
                    && in_array($this->selfServicePortalConfig->getSspInquiryCancelStateMachineEventName(), $sspInquiryTransfer->getManualEvents()),
                );
        }

        return $sspInquiryCollectionTransfer;
    }

    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return $sspInquiryCriteriaTransfer->getInclude() && $sspInquiryCriteriaTransfer->getInclude()->getWithManualEvents();
    }
}
