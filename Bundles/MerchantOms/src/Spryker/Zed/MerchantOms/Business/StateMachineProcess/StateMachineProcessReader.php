<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\StateMachineProcess;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StateMachineProcessCriteriaTransfer;
use Generated\Shared\Transfer\StateMachineProcessTransfer;
use Spryker\Zed\MerchantOms\Business\Exception\MerchantNotFoundException;
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
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    public function resolveMerchantStateMachineProcess(string $merchantReference): StateMachineProcessTransfer
    {
        $merchantTransfer = $this->getMerchantByReference($merchantReference);

        $stateMachineProcessTransfer = $this->stateMachineFacade->findStateMachineProcess(
            (new StateMachineProcessCriteriaTransfer())
                ->setIdStateMachineProcess($merchantTransfer->getFkStateMachineProcess())
        );

        if (!$stateMachineProcessTransfer) {
            $stateMachineProcessTransfer = (new StateMachineProcessTransfer())
                ->setStateMachineName(MerchantOmsConfig::MERCHANT_OMS_STATE_MACHINE_NAME)
                ->setProcessName($this->merchantOmsConfig->getMerchantOmsDefaultProcessName());
        }

        return $stateMachineProcessTransfer;
    }

    /**
     * @param string $merchantReference
     *
     * @throws \Spryker\Zed\MerchantOms\Business\Exception\MerchantNotFoundException
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function getMerchantByReference(string $merchantReference): MerchantTransfer
    {
        $merchantTransfer = $this->merchantFacade->findOne(
            (new MerchantCriteriaTransfer())->setMerchantReference($merchantReference)
        );

        if (!$merchantTransfer) {
            throw new MerchantNotFoundException(sprintf('Merchant with reference "%s" is not found.', $merchantReference));
        }

        return $merchantTransfer;
    }
}
