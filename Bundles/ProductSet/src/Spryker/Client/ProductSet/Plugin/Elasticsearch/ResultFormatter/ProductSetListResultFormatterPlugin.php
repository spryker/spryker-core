<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSet\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\ProductSet\ProductSetFactory getFactory()
 */
class ProductSetListResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'productSets';

    /**
     * @api
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param array $requestParameters
     *
     * @return mixed
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters)
    {
        $productSets = [];
        foreach ($searchResult->getResults() as $document) {
            $productSetStorageData = $document->getSource()[PageIndexMap::SEARCH_RESULT_DATA];
            $productSetStorageTransfer = $this->mapToTransfer($productSetStorageData);

            $productSets[] = $productSetStorageTransfer;
        }

        return $productSets;
    }

    /**
     * @param array $productSetStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer
     */
    protected function mapToTransfer(array $productSetStorageData)
    {
        return $this->getFactory()
            ->createProductSetStorageMapper()
            ->mapDataToTransfer($productSetStorageData);
    }
}
