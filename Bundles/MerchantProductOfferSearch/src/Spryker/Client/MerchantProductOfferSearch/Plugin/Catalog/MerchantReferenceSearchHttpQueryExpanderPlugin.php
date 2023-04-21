<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProductOfferSearch\Plugin\Catalog;

use Generated\Shared\Transfer\SearchQueryValueFacetFilterTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @deprecated Will be removed without replacement. Exists only for BC reasons.
 */
class MerchantReferenceSearchHttpQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const MERCHANT_NAME = 'merchant_name';

    /**
     * {@inheritDoc}
     * - Adds filter by merchant reference to query.
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
        return $this->addMerchantNameFilterToQuery($searchQuery, $requestParameters);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addMerchantNameFilterToQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $merchantNames = $requestParameters[static::MERCHANT_NAME] ?? null;

        if ($merchantNames) {
            $searchQuery->getSearchQuery()->addSearchQueryFacetFilter(
                (new SearchQueryValueFacetFilterTransfer())
                    ->setFieldName(static::MERCHANT_NAME)
                    ->setValues($merchantNames),
            );
        }

        return $searchQuery;
    }
}
