<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\SignalHandler;

interface WorkerSignalHandlerInterface
{
    /**
     * @return void
     */
    public function handle(): void;

    /**
     * @param int[] $signals
     * @param callable $handler
     *
     * @return void
     */
    public function attach(array $signals, callable $handler): void;
}
