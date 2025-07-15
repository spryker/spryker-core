<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\DataSet\SspInquiryDataSetInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspInquiryStateMachineWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     */
    public function __construct(
        protected StateMachineFacadeInterface $stateMachineFacade,
        protected SelfServicePortalConfig $selfServicePortalConfig
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<\Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry> $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[SspInquiryDataSetInterface::ID_SSP_INQUIRY])) {
            return;
        }

        $this->stateMachineFacade->triggerForNewStateMachineItem(
            (new StateMachineProcessTransfer())
                ->setStateMachineName($this->selfServicePortalConfig->getInquiryStateMachineName())
                ->setProcessName($this->selfServicePortalConfig->getSspInquiryStateMachineProcessInquiryTypeMap()[$dataSet[SspInquiryDataSetInterface::TYPE]]),
            $dataSet[SspInquiryDataSetInterface::ID_SSP_INQUIRY],
        );
    }
}
