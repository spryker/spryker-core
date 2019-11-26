<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\StateMachine\Mocks;

use Spryker\Zed\StateMachine\StateMachineConfig as SprykerStateMachineConfig;

class StateMachineConfig extends SprykerStateMachineConfig
{
    /**
     * @return string
     */
    public function getPathToStateMachineXmlFiles(): string
    {
        return realpath(__DIR__ . '/../_support/Fixtures');
    }
}
