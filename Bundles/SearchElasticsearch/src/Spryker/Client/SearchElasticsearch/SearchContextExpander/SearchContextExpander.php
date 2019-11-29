<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\SearchContextExpander;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;

class SearchContextExpander implements SearchContextExpanderInterface
{
    /**
     * @var \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface
     */
    protected $indexNameResolver;

    /**
     * @param \Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface $indexNameResolver
     */
    public function __construct(IndexNameResolverInterface $indexNameResolver)
    {
        $this->indexNameResolver = $indexNameResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        $searchContextTransfer = $this->expandSearchContextWithElasticsearchContext($searchContextTransfer);
        $searchContextTransfer = $this->addIndexNameToElasticsearchContext($searchContextTransfer);

        return $searchContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function expandSearchContextWithElasticsearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        $elasticsearchSearchContextTransfer = new ElasticsearchSearchContextTransfer();
        $searchContextTransfer->setElasticsearchContext($elasticsearchSearchContextTransfer);

        return $searchContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function addIndexNameToElasticsearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        $sourceIdentifier = $searchContextTransfer->requireSourceIdentifier()->getSourceIdentifier();
        $indexName = $this->indexNameResolver->resolve($sourceIdentifier);
        $searchContextTransfer->getElasticsearchContext()->setIndexName($indexName);

        return $searchContextTransfer;
    }
}
