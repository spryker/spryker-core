<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Plugin\Elasticsearch\Query;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspAssetSearchQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands SSP asset search query with permissions, sorting and search criterias.
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        return $this->getFactory()
            ->createSspAssetSearchQueryExpander()
            ->expandQuery(
                $searchQuery,
                $requestParameters,
                $this->getFactory()->createSspAssetSearchPaginationConfigBuilder(),
                $this->getFactory()->createSspAssetSearchSortConfigBuilder(),
            );
    }
}
