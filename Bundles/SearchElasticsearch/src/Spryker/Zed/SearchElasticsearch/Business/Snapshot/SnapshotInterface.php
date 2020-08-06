<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace  Spryker\Zed\SearchElasticsearch\Business\Snapshot;

interface SnapshotInterface
{
    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function createSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool;

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool;

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshot(string $repositoryName, string $snapshotName): bool;

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function deleteSnapshot(string $repositoryName, string $snapshotName): bool;
}
