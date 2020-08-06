<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;

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
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = []);

    /**
     * Specification:
     * - Retrieves a document, stored for search, by document key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer;

    /**
     * Specification:
     * - Deletes a document available for this Client.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool;

    /**
     * Specification:
     * - Deletes multiple documents available for this Client.
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

    /**
     * Specification:
     * - Stores a document available for this Client.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool;

    /**
     * Specification:
     * - Stores multiple documents available for this Client.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchContextTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchContextTransfers): bool;

    /**
     * Specification:
     * - Returns a unique plugin name.
     *
     * @api
     *
     * @return string
     */
    public function getName(): string;
}
