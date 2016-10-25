<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\Catalog\CatalogFactory getFactory()
 *
 * @deprecated This result formatter uses a bad design to extract product data as it reads Storage per product. Use
 * \Spryker\Client\Catalog\Plugin\Elasticsearch\ResultFormatter\RawCatalogSearchResultFormatterPlugin instead.
 */
class CatalogSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{

    const NAME = 'products';

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return array
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $catalogModel = $this->getFactory()->createCatalogModel();

        $products = [];
        $productIds = $this->extractProductIdsFromResultSet($searchResult);
        if ($productIds) {
            $products = $catalogModel->getProductDataByIds($productIds);
        }

        return $products;
    }

    /**
     * @param \Elastica\ResultSet $resultSet
     *
     * @return array
     */
    public function extractProductIdsFromResultSet(ResultSet $resultSet)
    {
        $abstractProductIds = [];
        foreach ($resultSet->getResults() as $result) {
            $product = $result->getSource();
            if (isset($product[PageIndexMap::SEARCH_RESULT_DATA]['id_product_abstract'])) {
                $abstractProductIds[] = $product[PageIndexMap::SEARCH_RESULT_DATA]['id_product_abstract'];
            }
        }

        return $abstractProductIds;
    }

}
