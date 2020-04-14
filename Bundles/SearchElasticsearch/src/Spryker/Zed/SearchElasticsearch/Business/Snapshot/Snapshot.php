<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Snapshot;

use Elastica\Snapshot as ElasticaSnapshot;
use RuntimeException;

class Snapshot implements SnapshotInterface
{
    /**
     * @var \Elastica\Snapshot
     */
    protected $elasticaSnapshot;

    /**
     * @param \Elastica\Snapshot $elasticaSnapshot
     */
    public function __construct(ElasticaSnapshot $elasticaSnapshot)
    {
        $this->elasticaSnapshot = $elasticaSnapshot;
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function createSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool
    {
        return $this->elasticaSnapshot->createSnapshot($repositoryName, $snapshotName, $options, true)->isOk();
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool
    {
        return $this->elasticaSnapshot->restoreSnapshot($repositoryName, $snapshotName, $options, true)->isOk();
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshot(string $repositoryName, string $snapshotName): bool
    {
        try {
            $this->elasticaSnapshot->getSnapshot($repositoryName, $snapshotName);

            return true;
        } catch (RuntimeException $exception) {
            return false;
        }
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function deleteSnapshot(string $repositoryName, string $snapshotName): bool
    {
        return $this->elasticaSnapshot->deleteSnapshot($repositoryName, $snapshotName)->isOk();
    }
}
