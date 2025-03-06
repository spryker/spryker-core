<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate;

use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use InvalidArgumentException;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class StateMachineSspInquiryPostCreateHook implements SspInquiryPostCreateHookInterface
{
    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     */
    public function __construct(
        protected SspInquiryManagementConfig $sspInquiryManagementConfig,
        protected StateMachineFacadeInterface $stateMachineFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @throws \InvalidArgumentException
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function execute(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        $processName = $this->sspInquiryManagementConfig->getSspInquiryStateMachineProcessSspInquiryTypeMap()[$sspInquiryTransfer->getType()] ?? null;
        if (!$processName) {
            throw new InvalidArgumentException(
                sprintf('There is no process name for ssp inquiry type %s', $sspInquiryTransfer->getType()),
            );
        }

        $stateMachineProcessTransfer = (new StateMachineProcessTransfer())
        ->setProcessName($this->sspInquiryManagementConfig->getSspInquiryStateMachineProcessSspInquiryTypeMap()[$sspInquiryTransfer->getType()])
        ->setStateMachineName($this->sspInquiryManagementConfig->getSspInquiryStateMachineName());

        $this->stateMachineFacade->triggerForNewStateMachineItem(
            $stateMachineProcessTransfer,
            (int)$sspInquiryTransfer->getIdSspInquiry(),
        );

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryTransfer $sspInquiryTransfer): bool
    {
        return $sspInquiryTransfer->getStatus() === null;
    }
}
