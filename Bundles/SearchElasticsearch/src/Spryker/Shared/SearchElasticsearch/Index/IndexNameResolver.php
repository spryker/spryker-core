<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\Index;

use Spryker\Shared\SearchElasticsearch\Exception\IndexNameException;

class IndexNameResolver implements IndexNameResolverInterface
{
    /**
     * @var array
     */
    protected $indexNameMap;

    /**
     * @param array $indexNameMap
     */
    public function __construct(array $indexNameMap)
    {
        $this->indexNameMap = $indexNameMap;
    }

    /**
     * @param string $indexName
     *
     * @throws \Spryker\Shared\SearchElasticsearch\Exception\IndexNameException
     *
     * @return string
     */
    public function resolve(string $indexName): string
    {
        if (isset($this->indexNameMap[$indexName])) {
            return $this->indexNameMap[$indexName];
        }

        throw new IndexNameException(sprintf('Could not map index name "%s". Please make sure that you configured your index name map correctly in the config_* files.', $indexName));
    }
}
