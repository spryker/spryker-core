<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\StateMachineProcess;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StateMachineProcessCriteriaFilterTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\MerchantOmsConfig;

class StateMachineProcessReader implements StateMachineProcessReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    protected $stateMachineFacade;

    /**
     * @var \Spryker\Zed\MerchantOms\MerchantOmsConfig
     */
    protected $merchantOmsConfig;

    /**
     * @param \Spryker\Zed\MerchantOms\MerchantOmsConfig $merchantOmsConfig
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     */
    public function __construct(
        MerchantOmsConfig $merchantOmsConfig,
        MerchantOmsToMerchantFacadeInterface $merchantFacade,
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->stateMachineFacade = $stateMachineFacade;
        $this->merchantOmsConfig = $merchantOmsConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    public function resolveMerchantStateMachineProcess(MerchantTransfer $merchantTransfer): StateMachineProcessTransfer
    {
        $stateMachineProcessTransfer = $this->findMerchantStateMachineProcess($merchantTransfer);

        if (!$stateMachineProcessTransfer) {
            $stateMachineProcessTransfer = (new StateMachineProcessTransfer())
                ->setStateMachineName(MerchantOmsConfig::MERCHANT_OMS_STATE_MACHINE_NAME)
                ->setProcessName($this->merchantOmsConfig->getMerchantOmsDefaultProcessName());
        }

        return $stateMachineProcessTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer|null
     */
    protected function findMerchantStateMachineProcess(MerchantTransfer $merchantTransfer): ?StateMachineProcessTransfer
    {
        $merchantTransfer = $this->merchantFacade->findOne(
            (new MerchantCriteriaFilterTransfer())->setMerchantReference($merchantTransfer->getMerchantReference())
        );

        if (!$merchantTransfer || !$merchantTransfer->getFkStateMachineProcess()) {
            return null;
        }

        return $this->stateMachineFacade->findStateMachineProcess(
            (new StateMachineProcessCriteriaFilterTransfer())->setIdStateMachineProcess($merchantTransfer->getFkStateMachineProcess())
        );
    }
}
