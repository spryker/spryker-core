<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\Step;

use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\DataImport\DataSet\SspInquiryDataSetInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryStateMachineWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     */
    public function __construct(
        protected StateMachineFacadeInterface $stateMachineFacade,
        protected SspInquiryManagementConfig $sspInquiryManagementConfig
    ) {
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<\Orm\Zed\SspInquiryManagement\Persistence\SpySspInquiry> $dataSet
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
                ->setStateMachineName($this->sspInquiryManagementConfig->getSspInquiryStateMachineName())
                ->setProcessName($this->sspInquiryManagementConfig->getSspInquiryStateMachineProcessSspInquiryTypeMap()[$dataSet[SspInquiryDataSetInterface::TYPE]]),
            $dataSet[SspInquiryDataSetInterface::ID_SSP_INQUIRY],
        );
    }
}
