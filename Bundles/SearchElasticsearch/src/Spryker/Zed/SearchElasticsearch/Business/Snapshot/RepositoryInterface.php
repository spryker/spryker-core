<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Snapshot;

interface RepositoryInterface
{
    /**
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return bool
     */
    public function registerSnapshotRepository(string $repositoryName, string $type = 'fs', array $settings = []): bool;

    /**
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository(string $repositoryName): bool;
}
