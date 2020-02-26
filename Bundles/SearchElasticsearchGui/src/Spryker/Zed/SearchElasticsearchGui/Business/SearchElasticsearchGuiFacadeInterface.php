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
     * - Returns a Elasticsearch document by its id and mapping type from an index.
     * - Returns SearchDocumentTransfer, which represents this document.
     *
     * @api
     *
     * @param string $documentId
     * @param string $indexName
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(string $documentId, string $indexName): SearchDocumentTransfer;
}
