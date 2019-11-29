<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\SearchContext;

use Generated\Shared\Transfer\SearchContextTransfer;

class SearchContextExpander implements SearchContextExpanderInterface
{
    /**
     * @var array|\Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface[]
     */
    protected $sourceIdentifierPlugins;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface[] $sourceIdentifierPlugins
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
    public function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        foreach ($this->sourceIdentifierPlugins as $sourceIdentifierPlugin) {
            $searchContextTransfer = $sourceIdentifierPlugin->expandSearchContext($searchContextTransfer);
        }

        return $searchContextTransfer;
    }
}
