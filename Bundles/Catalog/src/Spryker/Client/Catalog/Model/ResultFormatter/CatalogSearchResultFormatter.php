<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Model\ResultFormatter;

use Elastica\ResultSet;
use Spryker\Client\Catalog\Model\CatalogInterface;
use Spryker\Client\Catalog\Model\FacetConfig;
use Spryker\Client\Search\Model\ResultFormatter\AbstractElasticsearchResultFormatter;

class CatalogSearchResultFormatter extends AbstractElasticsearchResultFormatter
{

    /**
     * @var \Spryker\Client\Catalog\Model\CatalogInterface
     */
    protected $catalogModel;

    /**
     * @param CatalogInterface $catalogModel
     */
    public function __construct(CatalogInterface $catalogModel)
    {
        $this->catalogModel = $catalogModel;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function process(ResultSet $searchResult)
    {
        $products = [];
        $productIds = $this->extractProductIdsFromResultSet($searchResult);
        if ($productIds) {
            $products = $this->catalogModel->getProductDataByIds($productIds);
        }

        return [
            'products' => $products,
        ];
    }

    /**
     * @param \Elastica\ResultSet $resultSet
     *
     * @return array
     */
    public function extractProductIdsFromResultSet(ResultSet $resultSet)
    {
        $ids = [];
        foreach ($resultSet->getResults() as $result) {
            $product = $result->getSource();
            if (isset($product[FacetConfig::FIELD_SEARCH_RESULT_DATA]['id_product_abstract'])) {
                $ids[] = $product[FacetConfig::FIELD_SEARCH_RESULT_DATA]['id_product_abstract'];
            }
        }

        return $ids;
    }

}
