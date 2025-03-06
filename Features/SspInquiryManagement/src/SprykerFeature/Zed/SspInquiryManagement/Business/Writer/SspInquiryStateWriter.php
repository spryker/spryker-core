<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Writer;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryStateWriter implements SspInquiryStateWriterInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Business\Reader\SspInquiryReaderInterface $sspInquiryReader
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(
        protected SspInquiryReaderInterface $sspInquiryReader,
        protected StateMachineFacadeInterface $stateMachineFacade,
        protected SspInquiryManagementConfig $sspInquiryManagementConfig
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

         $sspInquiryCollectionTransfer = $this->sspInquiryReader->getSspInquiryCollection(
             (new SspInquiryCriteriaTransfer())
                ->setSspInquiryConditions(
                    (new SspInquiryConditionsTransfer())
                        ->setReferences($sspInquiryReferences),
                ),
         );

        $stateMachineItemTransfers = [];

        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $stateMachineItemTransfers[] = (new StateMachineItemTransfer())
                ->setStateMachineName($this->sspInquiryManagementConfig->getSspInquiryStateMachineName())
                ->setProcessName($this->sspInquiryManagementConfig->getSspInquiryStateMachineProcessSspInquiryTypeMap()[$sspInquiryTransfer->getType()])
                ->setIdItemState($sspInquiryTransfer->getFkStateMachineItemState())
                ->setIdentifier($sspInquiryTransfer->getIdSspInquiry())
                ->setEventName($this->sspInquiryManagementConfig->getSspInquiryCancelStateMachineEventName());
        }

        $updatedSspInquiriesCount = $this->stateMachineFacade->triggerEventForItems(
            (string)$this->sspInquiryManagementConfig->getSspInquiryCancelStateMachineEventName(),
            $stateMachineItemTransfers,
        );

         $sspInquiryCollectionResponseTransfer = new SspInquiryCollectionResponseTransfer();

        if ($updatedSspInquiriesCount !== count($sspInquiryReferences)) {
             $sspInquiryCollectionResponseTransfer->addError(
                 (new ErrorTransfer())
                    ->setMessage('The status change failed.'),
             );
        }

        return $sspInquiryCollectionResponseTransfer;
    }
}
