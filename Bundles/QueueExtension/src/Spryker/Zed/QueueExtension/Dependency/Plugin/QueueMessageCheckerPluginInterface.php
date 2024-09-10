<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QueueExtension\Dependency\Plugin;

/**
 * Used to check if queues are empty.
 */
interface QueueMessageCheckerPluginInterface
{
    /**
     * Specification:
     * - Used for checking if any of the applicable queues have messages.
     *
     * @api
     *
     * @param array<string> $queueNames
     *
     * @return bool
     */
    public function areQueuesEmpty(array $queueNames): bool;

    /**
     * Specification:
     * - Checks if this plugin is applicable to execute.
     *
     * @api
     *
     * @param string $adapterName
     *
     * @return bool
     */
    public function isApplicable(string $adapterName): bool;
}
