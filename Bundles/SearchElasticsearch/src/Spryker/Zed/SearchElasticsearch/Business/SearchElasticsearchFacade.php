<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchBusinessFactory getFactory()
 */
class SearchElasticsearchFacade extends AbstractFacade implements SearchElasticsearchFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function install(LoggerInterface $logger): void
    {
        $this->getFactory()->createIndexInstallBroker()->install($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function installMapper(LoggerInterface $logger): void
    {
        $this->getFactory()->createIndexMapperInstaller()->install($logger);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function openIndex(SearchContextTransfer $searchContextTransfer): bool
    {
        return $this->getFactory()->createIndex()->openIndex($searchContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function openIndexes(): bool
    {
        return $this->getFactory()->createIndex()->openIndexes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function closeIndex(SearchContextTransfer $searchContextTransfer): bool
    {
        return $this->getFactory()->createIndex()->closeIndex($searchContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function closeIndexes(): bool
    {
        return $this->getFactory()->createIndex()->closeIndexes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function deleteIndex(SearchContextTransfer $searchContextTransfer): bool
    {
        return $this->getFactory()->createIndex()->deleteIndex($searchContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function deleteIndexes(): bool
    {
        return $this->getFactory()->createIndex()->deleteIndexes();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return bool
     */
    public function copyIndex(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): bool
    {
        return $this->getFactory()->createIndex()->copyIndex($sourceSearchContextTransfer, $targetSearchContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchContextTransfer
     *
     * @return int
     */
    public function getDocumentsTotalCount(ElasticsearchSearchContextTransfer $elasticsearchContextTransfer): int
    {
        return $this->getFactory()->createIndex()->getDocumentsTotalCount($elasticsearchContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchContextTransfer
     *
     * @return array
     */
    public function getIndexMetaData(ElasticsearchSearchContextTransfer $elasticsearchContextTransfer): array
    {
        return $this->getFactory()->createIndex()->getIndexMetaData($elasticsearchContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getIndexNames(): array
    {
        return $this->getFactory()->createIndex()->getIndexNames();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function createSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool
    {
        return $this->getFactory()->createSnapshot()->createSnapshot($repositoryName, $snapshotName, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshot(string $repositoryName, string $snapshotName): bool
    {
        return $this->getFactory()->createSnapshot()->existsSnapshot($repositoryName, $snapshotName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function deleteSnapshot(string $repositoryName, string $snapshotName): bool
    {
        return $this->getFactory()->createSnapshot()->deleteSnapshot($repositoryName, $snapshotName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository(string $repositoryName): bool
    {
        return $this->getFactory()->createRepository()->existsSnapshotRepository($repositoryName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return bool
     */
    public function registerSnapshotRepository(string $repositoryName, string $type = 'fs', array $settings = []): bool
    {
        return $this->getFactory()->createRepository()->registerSnapshotRepository($repositoryName, $type, $settings);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool
    {
        return $this->getFactory()->createSnapshot()->restoreSnapshot($repositoryName, $snapshotName, $options);
    }
}
