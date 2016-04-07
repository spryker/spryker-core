<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Spryker\Zed\StateMachine\Dependency\Plugin;

use Generated\Shared\Transfer\StateMachineItemTransfer;

interface CommandPluginInterface
{
    /**
     * @param StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return bool
     */
    public function run(StateMachineItemTransfer $stateMachineItemTransfer);
}
