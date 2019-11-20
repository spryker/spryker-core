<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business;

use Generated\Shared\Transfer\DataMappingContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Psr\Log\LoggerInterface;

interface SearchElasticsearchFacadeInterface
{
    /**
     * Specification:
     * - Finds index definition files in modules.
     * - Installs or update indices by found index definition files.
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
     * - Opens all the existing Elasticsearch indices.
     *
     * @api
     *
     * @return bool
     */
    public function openIndices(): bool;

    /**
     * Specification:
     * - Closes an Elasticsearch index.
     * - The name of an index to be closed is carried by SearchContextTransfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer|null $searchContextTransfer
     *
     * @return bool
     */
    public function closeIndex(?SearchContextTransfer $searchContextTransfer = null): bool;

    /**
     * Specification:
     * - Closes all the existing Elasticsearch indices.
     *
     * @api
     *
     * @return bool
     */
    public function closeIndices(): bool;

    /**
     * Specification:
     * - Deletes an Elasticsearch index.
     * - The name of an index to be deleted is carried by SearchContextTransfer object.
     * - If no SearchContextTransfer object is passed, all the existing indices are deleted.
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
     * - Deletes all the existing Elasticsearch indices.
     *
     * @api
     *
     * @return bool
     */
    public function deleteIndices(): bool;

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

    /**
     * Specification:
     * - tba.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array;

    /**
     * Specification:
     * - tba.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapPageDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array;

    /**
     * Specification:
     * - tba.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapProductReviewDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array;
}
