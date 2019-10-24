<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchElasticsearch\SearchContext;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Shared\SearchElasticsearch\Index\IndexNameResolverInterface;

class SourceIdentifierMapper implements SourceIdentifierMapperInterface
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
    public function mapSourceIdentifier(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        $sourceIdentifier = $this->getSourceIdentifierFromSearchContextTransfer($searchContextTransfer);
        $indexName = $this->indexNameResolver->resolve($sourceIdentifier);
        $searchContextTransfer = $this->setIndexName($searchContextTransfer, $indexName);

        return $searchContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return string
     */
    protected function getSourceIdentifierFromSearchContextTransfer(SearchContextTransfer $searchContextTransfer): string
    {
        return $searchContextTransfer->requireSourceIdentifier()
            ->getSourceIdentifier();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     * @param string $indexName
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function setIndexName(SearchContextTransfer $searchContextTransfer, string $indexName): SearchContextTransfer
    {
        $elasticsearchSearchContextTransfer = $searchContextTransfer->getElasticsearchContext();

        if (!$elasticsearchSearchContextTransfer) {
            $elasticsearchSearchContextTransfer = new ElasticsearchSearchContextTransfer();
        }

        $elasticsearchSearchContextTransfer->setSourceName($indexName);
        $searchContextTransfer->setElasticsearchContext($elasticsearchSearchContextTransfer);

        return $searchContextTransfer;
    }
}
