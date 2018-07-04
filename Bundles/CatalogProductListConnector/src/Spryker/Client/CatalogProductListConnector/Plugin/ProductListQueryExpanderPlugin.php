<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogProductListConnector\Plugin;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\CatalogProductListConnector\CatalogProductListConnectorFactory getFactory()
 */
class ProductListQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $query = $searchQuery->getSearchQuery();

        $this->expandQueryWithBlacklistFilter($query);
        $this->expandQueryWithWhitelistFilter($query);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function expandQueryWithBlacklistFilter(Query $query): void
    {
        $blacklistIds = $this->getBlacklistIds();
        if (count($blacklistIds)) {
            $boolQuery = $this->getBoolQuery($query);
            $boolQuery->addMustNot($this->createBlacklistTermQuery($blacklistIds));
        }
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function expandQueryWithWhitelistFilter(Query $query): void
    {
        $whitelistIds = $this->getWhitelistIds();

        if (count($whitelistIds)) {
            $boolQuery = $this->getBoolQuery($query);
            $boolQuery->addFilter($this->createWhitelistTermQuery($whitelistIds));
        }
    }

    /**
     * @param array $blacklistIds
     *
     * @return \Elastica\Query\Terms
     */
    protected function createBlacklistTermQuery(array $blacklistIds): Terms
    {
        return new Terms(PageIndexMap::PRODUCT_LISTS_BLACKLISTS, $blacklistIds);
    }

    /**
     * @param array $whitelistIds
     *
     * @return \Elastica\Query\Terms
     */
    protected function createWhitelistTermQuery(array $whitelistIds): Terms
    {
        return new Terms(PageIndexMap::PRODUCT_LISTS_WHITELISTS, $whitelistIds);
    }

    /**
     * @return int[]
     */
    protected function getBlacklistIds(): array
    {
        $blacklistIds = [];
        $customerProductListCollectionTransfer = $this->findCustomerProductListCollection();

        if (!$customerProductListCollectionTransfer || !$customerProductListCollectionTransfer->getBlacklists()) {
            return $blacklistIds;
        }

        foreach ($customerProductListCollectionTransfer->getBlacklists() as $productListTransfer) {
            $blacklistIds[] = $productListTransfer->getIdProductList();
        }

        return $blacklistIds;
    }

    /**
     * @return int[]
     */
    protected function getWhitelistIds(): array
    {
        $whitelistIds = [];
        $customerProductListCollectionTransfer = $this->findCustomerProductListCollection();

        if (!$customerProductListCollectionTransfer || !$customerProductListCollectionTransfer->getWhitelists()) {
            return $whitelistIds;
        }

        foreach ($customerProductListCollectionTransfer->getWhitelists() as $productListTransfer) {
            $whitelistIds[] = $productListTransfer->getIdProductList();
        }

        return $whitelistIds;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer|null
     */
    protected function findCustomerProductListCollection(): ?CustomerProductListCollectionTransfer
    {
        if ($this->getCustomer() && $this->getCustomer()->getCustomerProductListCollection()) {
            return $this->getCustomer()->getCustomerProductListCollection();
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomer(): ?CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Product List Query Expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }
}
