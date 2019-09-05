<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Worker;

interface WorkerInterface
{
    /**
     * @param string $command
     * @param string[] $options
     *
     * @return void
     */
    public function start(string $command, array $options = []): void;
}
