<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Form\DataProvider;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Form\TriggerEventForm;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class TriggerEventFormDataProvider implements TriggerEventFormDataProviderInterface
{
    /**
     * @var \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface
     */
    protected SspInquiryManagementFacadeInterface $sspInquiryManagementFacade;

    /**
     * @var \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface
     */
    protected StateMachineFacadeInterface $stateMachineFacade;

    /**
     * @var \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig
     */
    protected SspInquiryManagementConfig $sspInquiryManagementConfig;

    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface $sspInquiryManagementFacade
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(
        SspInquiryManagementFacadeInterface $sspInquiryManagementFacade,
        StateMachineFacadeInterface $stateMachineFacade,
        SspInquiryManagementConfig $sspInquiryManagementConfig
    ) {
        $this->sspInquiryManagementFacade = $sspInquiryManagementFacade;
        $this->stateMachineFacade = $stateMachineFacade;
        $this->sspInquiryManagementConfig = $sspInquiryManagementConfig;
    }

    /**
     * @param int $idSspInquiry
     *
     * @return array<mixed>
     */
    public function getOptions(int $idSspInquiry): array
    {
         $sspInquiryCollectionTransfer = $this->sspInquiryManagementFacade->getSspInquiryCollection(
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
            ->setStateMachineName($this->sspInquiryManagementConfig->getSspInquiryStateMachineName())
            ->setProcessName($this->sspInquiryManagementConfig->getSspInquiryStateMachineProcessSspInquiryTypeMap()[$sspInquiryTransfer->getType()])
            ->setStateName($sspInquiryTransfer->getStatus());

        return [
            TriggerEventForm::OPTION_EVENT_NAMES => $this->stateMachineFacade->getManualEventsForStateMachineItem($stateMachineItemTransfer),
        ];
    }
}
