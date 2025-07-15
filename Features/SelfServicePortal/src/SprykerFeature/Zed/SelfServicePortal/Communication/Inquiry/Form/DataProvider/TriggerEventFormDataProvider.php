<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\TriggerEventForm;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class TriggerEventFormDataProvider implements TriggerEventFormDataProviderInterface
{
 /**
  * @param \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface $selfServicePortalFacade
  * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
  * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
  */
    public function __construct(
        protected SelfServicePortalFacadeInterface $selfServicePortalFacade,
        protected StateMachineFacadeInterface $stateMachineFacade,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    /**
     * @param int $idSspInquiry
     *
     * @return array<mixed>
     */
    public function getOptions(int $idSspInquiry): array
    {
         $sspInquiryCollectionTransfer = $this->selfServicePortalFacade->getSspInquiryCollection(
             (new SspInquiryCriteriaTransfer())
                ->setSspInquiryConditions(
                    (new SspInquiryConditionsTransfer())
                        ->setSspInquiryIds([$idSspInquiry]),
                ),
         );

        if (!$sspInquiryCollectionTransfer->getSspInquiries()->count()) {
            return [
                TriggerEventForm::OPTION_EVENT_NAMES => [],
            ];
        }

         $sspInquiryTransfer = $sspInquiryCollectionTransfer->getSspInquiries()->offsetGet(0);

        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier($sspInquiryTransfer->getIdSspInquiry())
            ->setStateMachineName($this->selfServicePortalConfig->getInquiryStateMachineName())
            ->setProcessName($this->selfServicePortalConfig->getSspInquiryStateMachineProcessInquiryTypeMap()[$sspInquiryTransfer->getType()])
            ->setStateName($sspInquiryTransfer->getStatus());

        return [
            TriggerEventForm::OPTION_EVENT_NAMES => $this->stateMachineFacade->getManualEventsForStateMachineItem($stateMachineItemTransfer),
        ];
    }
}
