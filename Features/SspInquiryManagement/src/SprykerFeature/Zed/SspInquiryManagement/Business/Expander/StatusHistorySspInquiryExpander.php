<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class StatusHistorySspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @var array<int>
     */
    protected static array $sspInquiryTypeIdStateMachineProcessMapCache = [];

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
     * @param \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $statusHistory = $this->stateMachineFacade->getStateHistoryByStateItemIdentifier(
                $this->getIdProcessBySspInquiryType($sspInquiryTransfer->getTypeOrFail()),
                $sspInquiryTransfer->getIdSspInquiryOrFail(),
            );

             $sspInquiryTransfer->setStatusHistory(new ArrayObject($statusHistory));
        }

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return bool
     */
    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return !$sspInquiryCriteriaTransfer->getInclude() || $sspInquiryCriteriaTransfer->getInclude()->getWithStatusHistory();
    }

    /**
     * @param string $type
     *
     * @return int
     */
    protected function getIdProcessBySspInquiryType(string $type): int
    {
        if (in_array($type, static::$sspInquiryTypeIdStateMachineProcessMapCache)) {
            return static::$sspInquiryTypeIdStateMachineProcessMapCache[$type];
        }

        $idProcess = $this->stateMachineFacade->getStateMachineProcessId(
            (new StateMachineProcessTransfer())
                ->setProcessName($this->sspInquiryManagementConfig->getSspInquiryStateMachineProcessSspInquiryTypeMap()[$type])
                ->setStateMachineName($this->sspInquiryManagementConfig->getSspInquiryStateMachineName()),
        );

        static::$sspInquiryTypeIdStateMachineProcessMapCache[$type] = $idProcess;

        return $idProcess;
    }
}
