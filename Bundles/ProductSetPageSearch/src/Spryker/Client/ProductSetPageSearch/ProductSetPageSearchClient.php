<?php

namespace Spryker\Client\ProductSetPageSearch;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductSetPageSearch\ProductSetPageSearchFactory getFactory()
 */
class ProductSetPageSearchClient extends AbstractClient implements ProductSetPageSearchClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function getProductSetList($limit = null, $offset = null)
    {
        $searchQuery = $this->getFactory()->createProductSetListQuery($limit, $offset);
        $resultFormatters = $this->getFactory()->createProductSetListResultFormatters();

        return $this
            ->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters);
    }
}
