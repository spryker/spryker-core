<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\StateMachineProcess;

use Generated\Shared\Transfer\StateMachineProcessTransfer;

interface StateMachineProcessReaderInterface
{
    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\StateMachineProcessTransfer
     */
    public function resolveMerchantStateMachineProcess(string $merchantReference): StateMachineProcessTransfer;
}
