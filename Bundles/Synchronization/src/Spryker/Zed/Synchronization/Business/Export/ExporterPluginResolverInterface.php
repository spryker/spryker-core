<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

interface ExporterPluginResolverInterface
{
    /**
     * @deprecated Use `Spryker\Zed\Synchronization\Business\Export\ExporterPluginResolverInterface::executeResolvedPluginsBySourcesWithIds()` instead.
     *
     * @param string[] $resources
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources): void;

    /**
     * @param string[] $resources
     * @param int[] $ids
     *
     * @return void
     */
    public function executeResolvedPluginsBySourcesWithIds(array $resources, array $ids): void;

    /**
     * @return string[]
     */
    public function getAvailableResourceNames(): array;
}
