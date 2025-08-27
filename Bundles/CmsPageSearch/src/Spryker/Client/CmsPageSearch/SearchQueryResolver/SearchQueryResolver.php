<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Client\CmsPageSearch\SearchQueryResolver;

use Spryker\Client\SearchExtension\Dependency\Plugin\QueryApplicabilityCheckerInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

class SearchQueryResolver implements SearchQueryResolverInterface
{
    /**
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface> $searchQueryPlugins
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $defaultSearchQuery
     */
    public function __construct(protected array $searchQueryPlugins, protected QueryInterface $defaultSearchQuery)
    {
    }

    /**
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function resolve(): QueryInterface
    {
        if ($this->searchQueryPlugins === []) {
            return $this->defaultSearchQuery;
        }

        foreach ($this->searchQueryPlugins as $searchQueryPlugin) {
            if ($searchQueryPlugin instanceof QueryApplicabilityCheckerInterface && $searchQueryPlugin->isApplicable()) {
                return $searchQueryPlugin;
            }
        }

        return end($this->searchQueryPlugins);
    }
}
