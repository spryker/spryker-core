<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Search\Dependency\Plugin\PageMapInterface;

interface SearchFacadeInterface
{
    /**
     * Specification:
     * - Loads index definition json files from the folders
     * - Installs Elasticsearch indexes and mapping types based on the loaded index definitions if they not exists already
     * - For each configured store a separated index name is generated
     * - The index is created for only the current store
     * - The name of the index is automatically prefixed with the store name + underscore
     * - Generates IndexMap class for each mapping type
     * - The generated IndexMaps are not store specific and has the class name of the mapping types suffixed with "IndexMap"
     * - The generated files will be removed and re-created always when install runs
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function install(LoggerInterface $messenger);

    /**
     * Specification:
     * - Returns the total number of documents in the current index if no indexName is passed.
     * - Returns the total number of documents in the passed indexName.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null);

    /**
     * Specification:
     * - Returns the metadata information from the current index if no indexName is passed.
     * - Returns the metadata information from the passed indexName.
     * - Returns an empty array if the index is not installed.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null);

    /**
     * Specification:
     * - Removes the current index if no indexName is passed.
     * - Removes the passed indexName.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return \Elastica\Response
     */
    public function delete(?string $indexName = null);

    /**
     * Specification:
     * - Returns a document from the current index with the given key in the given mapping type
     *
     * @api
     *
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type);

    /**
     * Specification:
     * - Runs a simple full text search for the given search string
     * - Returns the raw result set ordered by relevance
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array|\Elastica\ResultSet|mixed (@deprecated Only mixed will be supported with the next major)
     */
    public function searchKeys($searchString, $limit = null, $offset = null);

    /**
     * @api
     *
     * @deprecated Use transformPageMapToDocumentByMapperName() instead.
     *
     * Specification:
     * - Transforms a raw data array into an Elasticsearch "page" mapping type document
     * - The transformation is based on the given page map what configures which data goes into which field
     *
     * @param \Spryker\Zed\Search\Dependency\Plugin\PageMapInterface $pageMap
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function transformPageMapToDocument(PageMapInterface $pageMap, array $data, LocaleTransfer $localeTransfer);

    /**
     * Specification:
     * - Transforms a raw data array into an Elasticsearch "page" mapping type document
     * - The transformation is based on the given page map plugin name what configures which data goes into which field
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $mapperName
     *
     * @throws \Spryker\Zed\Search\Business\Exception\InvalidPropertyNameException
     *
     * @return array
     */
    public function transformPageMapToDocumentByMapperName(array $data, LocaleTransfer $localeTransfer, $mapperName);

    /**
     * Specification:
     * - Loads index definition json files from the folders
     * - Generates IndexMap class for each mapping type
     * - The generated IndexMaps are not store specific and has the class name of the mapping types suffixed with "IndexMap"
     * - The generated files will be removed and re-created always when the command runs
     *
     * @api
     *
     * @deprecated Use `\Spryker\Zed\Search\Business\SearchFacadeInterface::generateSourceMap()` instead.
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generatePageIndexMap(LoggerInterface $messenger);

    /**
     * Specification:
     * - Loads schema definition json files from the folders
     * - Creates or update IndexMapper classes by found schema definition files.
     * - The generated IndexMaps are not store specific and has the class name of the mapping types suffixed with "IndexMap".
     * - The generated files will be removed and re-created always when the command runs.
     *
     * @api
     *
     * @param \Psr\Log\LoggerInterface $messenger
     *
     * @return void
     */
    public function generateSourceMap(LoggerInterface $messenger): void;

    /**
     * Specification:
     * - Creates a Snapshot.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function createSnapshot($repositoryName, $snapshotName, $options = []);

    /**
     * Specification:
     * - Checks if a Snapshot exists.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function existsSnapshot($repositoryName, $snapshotName);

    /**
     * Specification:
     * - Deletes a Snapshot.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     *
     * @return bool
     */
    public function deleteSnapshot($repositoryName, $snapshotName);

    /**
     * Specification:
     * - Checks if a Snapshot repository exists.
     *
     * @api
     *
     * @param string $repositoryName
     *
     * @return bool
     */
    public function existsSnapshotRepository($repositoryName);

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
    public function createSnapshotRepository($repositoryName, $type = 'fs', $settings = []);

    /**
     * Specification:
     * - Restores a Snapshot.
     *
     * @api
     *
     * @param string $repositoryName
     * @param string $snapshotName
     * @param array $options
     *
     * @return bool
     */
    public function restoreSnapshot($repositoryName, $snapshotName, $options = []);

    /**
     * Specification:
     * - Closes an Index.
     *
     * @api
     *
     * @return bool
     */
    public function closeIndex();

    /**
     * Specification:
     * - Opens an Index.
     *
     * @api
     *
     * @return bool
     */
    public function openIndex(): bool;

    /**
     * Specification:
     * - Closes all indices.
     *
     * @api
     *
     * @return bool
     */
    public function closeAllIndices();

    /**
     * Specification:
     * - Copies one index to another index.
     *
     * @api
     *
     * @param string $source
     * @param string $target
     *
     * @return bool
     */
    public function copyIndex($source, $target);
}
