<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\SearchContext;

use Generated\Shared\Transfer\SearchContextTransfer;

class SearchContextMapper implements SearchContextMapperInterface
{
    /**
     * @var array|\Spryker\Client\SearchExtension\Dependency\Plugin\SourceIdentifiertMapperPluginInterface[]
     */
    protected $sourceIdentifierPlugins;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SourceIdentifiertMapperPluginInterface[] $sourceIdentifierPlugins
     */
    public function __construct(array $sourceIdentifierPlugins)
    {
        $this->sourceIdentifierPlugins = $sourceIdentifierPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function mapSourceIdentifier(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        foreach ($this->sourceIdentifierPlugins as $sourceIdentifierPlugin) {
            $searchContextTransfer = $sourceIdentifierPlugin->mapSourceIdentifier($searchContextTransfer);
        }

        return $searchContextTransfer;
    }
}
