<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Elastica\Exception\NotFoundException;
use Elastica\Exception\ResponseException;
use Elastica\Snapshot;
use RuntimeException;

class SnapshotHandler implements SnapshotHandlerInterface
{
    protected const TYPE_FILESYSTEM = 'fs';
    protected const SETTINGS_LOCATION = 'location';

    /**
     * @var \Elastica\Snapshot
     */
    protected $elasticaSnapshot;

    /**
     * @param \Elastica\Snapshot $elasticaSnapshot
     */
    public function __construct(Snapshot $elasticaSnapshot)
    {
        $this->elasticaSnapshot = $elasticaSnapshot;
    }

    /**
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return bool
     */
    public function registerSnapshotRepository($repositoryName, $type = self::TYPE_FILESYSTEM, $settings = [])
    {
        $settings = $this->buildRepositorySettings($repositoryName, $type, $settings);

        return $this->elasticaSnapshot->registerRepository($repositoryName, $type, $settings)->isOk();
    }

    /**
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository($repositoryName)
    {
        try {
            $this->elasticaSnapshot->getRepository($repositoryName);

            return true;
        } catch (ResponseException $exception) {
            return false;
        } catch (NotFoundException $exception) {
            return false;
        }
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function createSnapshot($repositoryName, $snapshotName, $options = [])
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
    public function createSnapshotAsync($repositoryName, $snapshotName, $options = [])
    {
        return $this->elasticaSnapshot->createSnapshot($repositoryName, $snapshotName, $options, false)->isOk();
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshot($repositoryName, $snapshotName, $options = [])
    {
        return $this->elasticaSnapshot->restoreSnapshot($repositoryName, $snapshotName, $options, true)->isOk();
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshotAsync($repositoryName, $snapshotName, $options = [])
    {
        return $this->elasticaSnapshot->restoreSnapshot($repositoryName, $snapshotName, $options, false)->isOk();
    }

    /**
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshot($repositoryName, $snapshotName)
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
    public function deleteSnapshot($repositoryName, $snapshotName)
    {
        return $this->elasticaSnapshot->deleteSnapshot($repositoryName, $snapshotName)->isOk();
    }

    /**
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return array
     */
    protected function buildRepositorySettings(string $repositoryName, string $type = self::TYPE_FILESYSTEM, array $settings = []): array
    {
        if ($type === static::TYPE_FILESYSTEM && !isset($settings[static::SETTINGS_LOCATION])) {
            $settings[static::SETTINGS_LOCATION] = $repositoryName;
        }

        return $settings;
    }
}
