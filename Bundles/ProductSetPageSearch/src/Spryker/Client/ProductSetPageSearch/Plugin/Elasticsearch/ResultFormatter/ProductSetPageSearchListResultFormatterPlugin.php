<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductSetPageSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\PageIndexMap;
use Spryker\Client\Search\Plugin\Elasticsearch\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

/**
 * @method \Spryker\Client\ProductSetPageSearch\ProductSetPageSearchFactory getFactory()
 */
class ProductSetPageSearchListResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
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
        $productSetPageSearchs = [];
        foreach ($searchResult->getResults() as $document) {
            $productSetPageSearchStorageData = $document->getSource()[PageIndexMap::SEARCH_RESULT_DATA];
            $productSetPageSearchStorageData['image_sets'] = $this->formatImageSets($productSetPageSearchStorageData['image_sets']);
            $ProductSetPageSearchStorageTransfer = $this->mapToTransfer($productSetPageSearchStorageData);

            $productSetPageSearchs[] = $ProductSetPageSearchStorageTransfer;
        }

        return $productSetPageSearchs;
    }

    /**
     * @param array $productSetPageSearchStorageData
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer
     */
    protected function mapToTransfer(array $productSetPageSearchStorageData)
    {
        return $this->getFactory()
            ->getProductSetStorageClient()
            ->mapProductSetStorageDataToTransfer($productSetPageSearchStorageData);
    }

    /**
     * @param array $images
     *
     * @return array
     */
    protected function formatImageSets(array $images)
    {
        $imageSets = [];
        foreach ($images as $key => $value) {
            $image = [];
            $image['name'] = $key;
            $image['images'] = $value;

            $imageSets[] = $image;
        }

        return $imageSets;
    }
}
