<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\SearchContextExpander;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\SearchElasticsearch\Index\IndexNameResolver\IndexNameResolverInterface;

class SearchContextExpander implements SearchContextExpanderInterface
{
    /**
     * @var \Spryker\Client\SearchElasticsearch\Index\IndexNameResolver\IndexNameResolverInterface
     */
    protected $indexNameResolver;

    /**
     * @param \Spryker\Client\SearchElasticsearch\Index\IndexNameResolver\IndexNameResolverInterface $indexNameResolver
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
        $sourceIdentifier = $searchContextTransfer->requireSourceIdentifier()->getSourceIdentifier();
        $indexName = $this->indexNameResolver->resolve($sourceIdentifier);
        $elasticsearchSearchContextTransfer = $this->createElasticsearchContext($indexName, $sourceIdentifier);
        $searchContextTransfer->setElasticsearchContext($elasticsearchSearchContextTransfer);

        return $searchContextTransfer;
    }

    /**
     * @param string $indexName
     * @param string $sourceIdentifier
     *
     * @return \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer
     */
    protected function createElasticsearchContext(string $indexName, string $sourceIdentifier): ElasticsearchSearchContextTransfer
    {
        $elasticsearchSearchContextTransfer = new ElasticsearchSearchContextTransfer();
        $elasticsearchSearchContextTransfer->setIndexName($indexName);

        /**
         * Source identifier will be used as type name instead of _doc for the sake of compatibility with Elasticsearch 5.
         */
        $elasticsearchSearchContextTransfer->setTypeName($sourceIdentifier);

        return $elasticsearchSearchContextTransfer;
    }
}
