<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\Plugin;

use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\SourceIdentifiertMapperPluginInterface;

/**
 * @method \Spryker\Client\SearchElasticsearch\SearchElasticsearchFactory getFactory()
 */
class ElasticsearchSourceIdentifiertMapperPlugin extends AbstractPlugin implements SourceIdentifiertMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps source identifier to Elasticsearch index name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function mapSourceIdentifier(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        return $this->getFactory()->createSearchContextMapper()->mapSourceIdentifier($searchContextTransfer);
    }
}
