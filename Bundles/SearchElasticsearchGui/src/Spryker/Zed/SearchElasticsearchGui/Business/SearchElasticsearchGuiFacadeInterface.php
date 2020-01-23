<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Business;

use Generated\Shared\Transfer\SearchDocumentTransfer;

interface SearchElasticsearchGuiFacadeInterface
{
    /**
     * Specification:
     * - Returns the total count of documents in an index.
     *
     * @api
     *
     * @param string $indexName
     *
     * @return int
     */
    public function getTotalCountOfDocumentsInIndex(string $indexName): int;

    /**
     * Specification:
     * - Returns metadata of an index.
     *
     * @api
     *
     * @param string $indexName
     *
     * @return array
     */
    public function getIndexMetaData(string $indexName): array;

    /**
     * Specification:
     * - Returns a Elasticsearch document by its id and mapping type from an index.
     * - Returns SearchDocumentTransfer, which represents this document.
     *
     * @api
     *
     * @param string $documentId
     * @param string $indexName
     * @param string $typeName
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(string $documentId, string $indexName, string $typeName): SearchDocumentTransfer;
}
