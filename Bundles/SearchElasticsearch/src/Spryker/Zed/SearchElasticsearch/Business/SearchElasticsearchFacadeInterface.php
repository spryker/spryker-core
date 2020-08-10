<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Psr\Log\LoggerInterface;

interface SearchElasticsearchFacadeInterface
{
    /**
     * Specification:
     * - Finds index definition files in modules.
     * - Installs or update indexes by found index definition files.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function install(LoggerInterface $logger): void;

    /**
     * Specification:
     * - Finds index definition files in modules.
     * - Creates or update IndexMapper classes by found index definition files.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function installMapper(LoggerInterface $logger): void;

    /**
     * Specification:
     * - Opens an Elasticsearch index.
     * - The name of an index to be open is carried by SearchContextTransfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function openIndex(SearchContextTransfer $searchContextTransfer): bool;

    /**
     * Specification:
     * - Opens all the existing Elasticsearch indexes.
     *
     * @api
     *
     * @return bool
     */
    public function openIndexes(): bool;

    /**
     * Specification:
     * - Closes an Elasticsearch index.
     * - The name of an index to be closed is carried by SearchContextTransfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function closeIndex(SearchContextTransfer $searchContextTransfer): bool;

    /**
     * Specification:
     * - Closes all the existing Elasticsearch indexes.
     *
     * @api
     *
     * @return bool
     */
    public function closeIndexes(): bool;

    /**
     * Specification:
     * - Deletes an Elasticsearch index.
     * - The name of an index to be deleted is carried by SearchContextTransfer object.
     * - If no SearchContextTransfer object is passed, all the existing indexes are deleted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function deleteIndex(SearchContextTransfer $searchContextTransfer): bool;

    /**
     * Specification:
     * - Deletes all the existing Elasticsearch indexes.
     *
     * @api
     *
     * @return bool
     */
    public function deleteIndexes(): bool;

    /**
     * Specification:
     * - Copies one index to another index.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return bool
     */
    public function copyIndex(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): bool;

    /**
     * Specification:
     * - Returns the total number of documents in an index.
     * - The name of an index to get metadata from is passed in ElasticsearchSearchContextTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchContextTransfer
     *
     * @return int
     */
    public function getDocumentsTotalCount(ElasticsearchSearchContextTransfer $elasticsearchContextTransfer): int;

    /**
     * Specification:
     * - Returns the metadata information from the index.
     * - Returns empty array if the index is not installed
     * - The name of and index to get metadata from is passed in ElasticsearchSearchContextTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchContextTransfer
     *
     * @return array
     */
    public function getIndexMetaData(ElasticsearchSearchContextTransfer $elasticsearchContextTransfer): array;

    /**
     * Specification:
     * - Returns the names of all the available indexes present in Elasticsearch.
     * - Available indexes are those matching {@link \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConfig::SUPPORTED_SOURCE_IDENTIFIERS}.
     *
     * @api
     *
     * @return string[]
     */
    public function getIndexNames(): array;

    /**
     * Specification:
     * - Creates a snapshot.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function createSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool;

    /**
     * Specification:
     * - Checks if a snapshot exists.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshot(string $repositoryName, string $snapshotName): bool;

    /**
     * Specification:
     * - Deletes a snapshot.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function deleteSnapshot(string $repositoryName, string $snapshotName): bool;

    /**
     * Specification:
     * - Checks if a snapshot repository exists.
     *
     * @api
     *
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository(string $repositoryName): bool;

    /**
     * Specification:
     * - Creates a Snapshot repository.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $type
     * @param array $settings
     *
     * @return bool
     */
    public function registerSnapshotRepository(string $repositoryName, string $type = 'fs', array $settings = []): bool;

    /**
     * Specification:
     * - Restores a snapshot repository.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshot(string $repositoryName, string $snapshotName, array $options = []): bool;
}
