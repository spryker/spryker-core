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
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
     */
    public function __construct(
        MerchantOmsToMerchantFacadeInterface $merchantFacade,
        MerchantOmsToStateMachineFacadeInterface $stateMachineFacade
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->stateMachineFacade = $stateMachineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer|null
     */
    public function findStateMachineProcessByMerchant(MerchantTransfer $merchantTransfer): ?StateMachineProcessTransfer
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
