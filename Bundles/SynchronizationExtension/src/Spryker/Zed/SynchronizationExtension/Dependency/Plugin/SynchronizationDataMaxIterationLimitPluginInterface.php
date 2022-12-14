<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SynchronizationExtension\Dependency\Plugin;

/**
 * Provides maxIterationLimit for SynchronizationDataPluginIterator.
 *
 * Implement this interface if you need to set an explicit maxIterationLimit.
 * The Iterator can proceed until maxIterationLimit is reached even if intermittent result sets are empty.
 *
 * @see \Spryker\Zed\Synchronization\Business\Iterator\SynchronizationDataBulkRepositoryPluginIterator
 */
interface SynchronizationDataMaxIterationLimitPluginInterface
{
    /**
     * Specification:
     *  - Returns a maximum iteration value.
     *
     * @api
     *
     * @return int
     */
    public function getMaxIterationLimit(): int;
}
