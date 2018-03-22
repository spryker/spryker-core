<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Business\Task;

interface TaskManagerInterface
{
    /**
     * @param string $queueName
     * @param array $options
     *
     * @return void
     */
    public function run($queueName, array $options = []);
}
