<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspInquiryStateWriter implements SspInquiryStateWriterInterface
{
    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface $sspInquiryReader
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     */
    public function __construct(
        protected SspInquiryReaderInterface $sspInquiryReader,
        protected StateMachineFacadeInterface $stateMachineFacade,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiry(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        $sspInquiryReferences = [];

        foreach ($sspInquiryCollectionRequestTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $sspInquiryReferences[] = $sspInquiryTransfer->getReference();
        }

         $sspInquiryReferences = array_filter($sspInquiryReferences);

         $sspInquiryConditionsTransfer = $sspInquiryCollectionRequestTransfer->getSspInquiryConditions() ?: new SspInquiryConditionsTransfer();
         $sspInquiryConditionsTransfer->setReferences($sspInquiryReferences);

        $sspInquiryCollectionTransfer = $this->sspInquiryReader->getSspInquiryCollection(
            (new SspInquiryCriteriaTransfer())
                ->setSspInquiryConditions(
                    $sspInquiryConditionsTransfer,
                ),
        );

        $stateMachineItemTransfers = [];

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $stateMachineItemTransfers[] = (new StateMachineItemTransfer())
                ->setStateMachineName($this->selfServicePortalConfig->getInquiryStateMachineName())
                ->setProcessName($this->selfServicePortalConfig->getSspInquiryStateMachineProcessInquiryTypeMap()[$sspInquiryTransfer->getType()])
                ->setIdItemState((int)$sspInquiryTransfer->getStateMachineItemStateOrFail()->getIdStateMachineItemStateOrFail())
                ->setIdentifier($sspInquiryTransfer->getIdSspInquiry())
                ->setEventName($this->selfServicePortalConfig->getSspInquiryCancelStateMachineEventName());
        }

        $updatedSspInquiriesCount = $this->stateMachineFacade->triggerEventForItems(
            $this->selfServicePortalConfig->getSspInquiryCancelStateMachineEventName(),
            $stateMachineItemTransfers,
        );

        $sspInquiryCollectionResponseTransfer = new SspInquiryCollectionResponseTransfer();

        if ($updatedSspInquiriesCount !== count($sspInquiryReferences)) {
            $sspInquiryCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage('The status change failed for some inquiries.'),
            );
        }

        return $sspInquiryCollectionResponseTransfer;
    }
}
