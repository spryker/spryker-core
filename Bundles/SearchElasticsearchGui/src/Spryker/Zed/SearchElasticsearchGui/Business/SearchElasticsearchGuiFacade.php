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
     * @param string $documentId
     * @param string $indexName
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(string $documentId, string $indexName): SearchDocumentTransfer
    {
        return $this->getFactory()->createDocumentReader()->readDocument($documentId, $indexName);
    }
}
