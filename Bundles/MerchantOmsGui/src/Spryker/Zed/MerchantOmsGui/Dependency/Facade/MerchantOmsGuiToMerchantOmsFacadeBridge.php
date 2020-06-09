<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOmsGui\Dependency\Facade;

use Generated\Shared\Transfer\StateMachineItemTransfer;

class MerchantOmsGuiToMerchantOmsFacadeBridge implements MerchantOmsGuiToMerchantOmsFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface
     */
    protected $merchantOmsFacade;

    /**
     * @param \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface $merchantOmsFacade
     */
    public function __construct($merchantOmsFacade)
    {
        $this->merchantOmsFacade = $merchantOmsFacade;
    }

    /**
     * @param int $idOrderItem
     *
     * @return \Spryker\Zed\MerchantOms\Business\StateMachineState\StateMachineItemTransfer
     */
    public function getCurrentState(int $idOrderItem): StateMachineItemTransfer
    {
        return $this->merchantOmsFacade->getCurrentState($idOrderItem);
    }
}
