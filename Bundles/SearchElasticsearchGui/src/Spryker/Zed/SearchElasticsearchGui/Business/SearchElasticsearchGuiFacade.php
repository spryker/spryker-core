<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearchGui\Business;

use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SearchElasticsearchGui\Business\SearchElasticsearchGuiBusinessFactory getFactory()
 */
class SearchElasticsearchGuiFacade extends AbstractFacade implements SearchElasticsearchGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $indexName
     *
     * @return int
     */
    public function getTotalCountOfDocumentsInIndex(string $indexName): int
    {
        return $this->getFactory()->createDocumentCounter()->getTotalCountOfDocumentsInIndex($indexName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $indexName
     *
     * @return array
     */
    public function getIndexMetaData(string $indexName): array
    {
        return $this->getFactory()->createIndexMetaDataReader()->getIndexMetaData($indexName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $documentId
     * @param string $indexName
     * @param string $typeName
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(string $documentId, string $indexName, string $typeName): SearchDocumentTransfer
    {
        return $this->getFactory()->createDocumentReader()->readDocument($documentId, $indexName, $typeName);
    }
}
