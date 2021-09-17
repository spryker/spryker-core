<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Export;

interface ExporterPluginResolverInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\Synchronization\Business\Export\ExporterPluginResolverInterface::executeResolvedPluginsBySourcesWithIds()} instead.
     *
     * @param array<string> $resources
     *
     * @return void
     */
    public function executeResolvedPluginsBySources(array $resources): void;

    /**
     * @param array<string> $resources
     * @param array<int> $ids
     *
     * @return void
     */
    public function executeResolvedPluginsBySourcesWithIds(array $resources, array $ids): void;

    /**
     * @return array<string>
     */
    public function getAvailableResourceNames(): array;
}
