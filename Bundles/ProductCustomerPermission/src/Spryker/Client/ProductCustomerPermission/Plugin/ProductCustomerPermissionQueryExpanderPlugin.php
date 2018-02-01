<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission\Plugin;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\HasChild;
use Elastica\Query\Match;
use Generated\Shared\Search\CustomerPageIndexMap;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\Search\Dependency\Plugin\QueryInterface;
use Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionConfig;

/**
 * @method \Spryker\Client\ProductCustomerPermission\ProductCustomerPermissionFactory getFactory()
 */
class ProductCustomerPermissionQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Spryker\Client\Search\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = [])
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer) {
            $productPriceFilter = $this->createCustomerFilter($customerTransfer->getIdCustomer());
            $boolQuery = $this->getBoolQuery($searchQuery->getSearchQuery());
            $boolQuery->addFilter($productPriceFilter);
        }

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query)
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Product Customer Permission Expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }

    /**
     * @param int $customerId
     *
     * @return \Elastica\Query\Match
     */
    protected function createCustomerQuery(int $customerId)
    {
        return (new Match())
            ->setField(CustomerPageIndexMap::ID_CUSTOMER, $customerId);
    }

    /**
     * @param int $customerId
     *
     * @return \Elastica\Query\HasChild
     */
    protected function createCustomerFilter(int $customerId)
    {
        $customerQuery = $this->createCustomerQuery($customerId);
        return (new HasChild($customerQuery))
            ->setType(ProductCustomerPermissionConfig::ELASTICSEARCH_INDEX_TYPE_NAME);
    }
}
