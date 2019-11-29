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
    protected $searchContextExpanderPlugins;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextExpanderPluginInterface[] $searchContextExpanderPlugins
     */
    public function __construct(array $searchContextExpanderPlugins)
    {
        $this->searchContextExpanderPlugins = $searchContextExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        foreach ($this->searchContextExpanderPlugins as $searchContextExpanderPlugin) {
            $searchContextTransfer = $searchContextExpanderPlugin->expandSearchContext($searchContextTransfer);
        }

        return $searchContextTransfer;
    }
}
