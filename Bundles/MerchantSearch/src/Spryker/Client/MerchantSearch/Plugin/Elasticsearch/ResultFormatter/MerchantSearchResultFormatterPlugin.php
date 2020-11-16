<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\MerchantIndexMap;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

class MerchantSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    protected const NAME = 'MerchantSearchCollection';
    protected const ITEMS_PER_PAGE = 'from';

    /**
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param mixed[] $requestParameters
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): MerchantSearchCollectionTransfer
    {
        $merchantSearchCollection = new MerchantSearchCollectionTransfer();

        foreach ($searchResult->getResults() as $document) {
            $merchantSearchCollection->addMerchant(
                $this->getMappedMerchantSearchTransfer($document->getSource()[MerchantIndexMap::SEARCH_RESULT_DATA])
            );
        }

        $merchantSearchCollection->setNbResults($searchResult->getTotalHits())
            ->setIpp(
                $searchResult->getQuery()->getParam(static::ITEMS_PER_PAGE)
            );

        return $merchantSearchCollection;
    }

    /**
     * @param string[] $data
     *
     * @return \Generated\Shared\Transfer\MerchantSearchTransfer
     */
    protected function getMappedMerchantSearchTransfer(array $data): MerchantSearchTransfer
    {
        return (new MerchantSearchTransfer())->fromArray($data, true);
    }
}
