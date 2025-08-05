<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander;

use ArrayObject;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class StatusHistorySspInquiryExpander implements SspInquiryExpanderInterface
{
    /**
     * @var array<int>
     */
    protected static array $sspInquiryTypeIdStateMachineProcessMapCache = [];

    public function __construct(
        protected SelfServicePortalConfig $selfServicePortalConfig,
        protected StateMachineFacadeInterface $stateMachineFacade
    ) {
    }

    public function expand(SspInquiryCollectionTransfer $sspInquiryCollectionTransfer): SspInquiryCollectionTransfer
    {
        foreach ($sspInquiryCollectionTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $statusHistory = $this->stateMachineFacade->getStateHistoryByStateItemIdentifier(
                $this->getIdProcessByInquiryType($sspInquiryTransfer->getTypeOrFail()),
                $sspInquiryTransfer->getIdSspInquiryOrFail(),
            );

             $sspInquiryTransfer->setStatusHistory(new ArrayObject($statusHistory));
        }

        return $sspInquiryCollectionTransfer;
    }

    public function isApplicable(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): bool
    {
        return !$sspInquiryCriteriaTransfer->getInclude() || $sspInquiryCriteriaTransfer->getInclude()->getWithStatusHistory();
    }

    protected function getIdProcessByInquiryType(string $type): int
    {
        if (in_array($type, static::$sspInquiryTypeIdStateMachineProcessMapCache)) {
            return static::$sspInquiryTypeIdStateMachineProcessMapCache[$type];
        }

        $idProcess = $this->stateMachineFacade->getStateMachineProcessId(
            (new StateMachineProcessTransfer())
                ->setProcessName($this->selfServicePortalConfig->getSspInquiryStateMachineProcessInquiryTypeMap()[$type])
                ->setStateMachineName($this->selfServicePortalConfig->getInquiryStateMachineName()),
        );

        static::$sspInquiryTypeIdStateMachineProcessMapCache[$type] = $idProcess;

        return $idProcess;
    }
}
