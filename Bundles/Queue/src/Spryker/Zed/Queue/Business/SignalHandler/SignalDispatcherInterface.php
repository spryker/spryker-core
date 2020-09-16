<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\SignalHandler;

interface SignalDispatcherInterface
{
    /**
     * @param int[] $signals
     *
     * @return void
     */
    public function dispatch(array $signals): void;
}
