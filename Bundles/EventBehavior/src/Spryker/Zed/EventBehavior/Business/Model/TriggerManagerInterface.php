<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Business\Model;

interface TriggerManagerInterface
{
    /**
     * @return void
     */
    public function triggerRuntimeEvents();

    /**
     * @return void
     */
    public function triggerLostEvents();
}
