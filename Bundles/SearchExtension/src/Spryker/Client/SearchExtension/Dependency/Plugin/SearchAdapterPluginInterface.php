<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SearchContextTransfer;

interface SearchAdapterPluginInterface
{
    /**
     * Specification:
     * - Runs the search query based on the search configuration provided by this client.
     * - If there's no result formatter given then the raw search result will be returned.
     * - The formatted search result will be an associative array where the keys are the name and the values are the formatted results.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, SearchContextTransfer $searchContextTransfer, array $resultFormatters = [], array $requestParameters = []);

    /**
     * Specification:
     * - Returns the number of documents in index if an indexName is passed.
     * - Returns the number of documents in all indices if indexName is not passed.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return int
     */
    public function getTotalCount(?string $indexName = null): int;

    /**
     * Specification:
     * - Returns the meta data of an index if indexName is passed.
     * - Returns the meta data of all indices if indexName is not passed.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return array
     */
    public function getMetaData(?string $indexName = null): array;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     * @param string $indexName
     *
     * @return mixed
     */
    public function read(string $key, string $indexName);

    /**
     * Specification:
     * - Deletes one index available for this Client if indexName is passed.
     * - Deletes all indices available for this Client if indexName is not passed.
     *
     * @api
     *
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(?string $indexName = null): bool;

    /**
     * Specification:
     * - Deletes documents available for this Client.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool;

    /**
     * Specification:
     * - Returns true if this Client can perform search within the specified context.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function isApplicable(SearchContextTransfer $searchContextTransfer): bool;
}
