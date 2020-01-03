<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Snapshot;

use Elastica\Exception\NotFoundException;
use Elastica\Exception\ResponseException;
use Elastica\Snapshot as ElasticaSnapshot;

class Repository implements RepositoryInterface
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
    public function __construct(ElasticaSnapshot $elasticaSnapshot)
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
    public function registerSnapshotRepository(string $repositoryName, string $type = self::TYPE_FILESYSTEM, array $settings = []): bool
    {
        $settings = $this->buildRepositorySettings($repositoryName, $type, $settings);

        return $this->elasticaSnapshot->registerRepository($repositoryName, $type, $settings)->isOk();
    }

    /**
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository(string $repositoryName): bool
    {
        try {
            $this->elasticaSnapshot->getRepository($repositoryName);

            return true;
        } catch (ResponseException | NotFoundException $exception) {
            return false;
        }
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
