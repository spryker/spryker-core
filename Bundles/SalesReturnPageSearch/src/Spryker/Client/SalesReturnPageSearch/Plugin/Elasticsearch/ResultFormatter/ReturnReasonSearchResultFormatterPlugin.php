<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturnPageSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\ReturnReasonIndexMap;
use Generated\Shared\Transfer\ReturnReasonPageSearchCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonPageSearchTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

class ReturnReasonSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    public const NAME = 'ReturnReasonCollection';

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
     * @param array $requestParameters
     *
     * @return \Generated\Shared\Transfer\ReturnReasonPageSearchCollectionTransfer
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): ReturnReasonPageSearchCollectionTransfer
    {
        $returnReasonPageSearchCollection = new ReturnReasonPageSearchCollectionTransfer();

        foreach ($searchResult->getResults() as $document) {
            $returnReasonPageSearchCollection->addReturnReason(
                $this->getMappedReturnReasonPageSearchTransfer($document->getSource()[ReturnReasonIndexMap::SEARCH_RESULT_DATA])
            );
        }

        $returnReasonPageSearchCollection->setNbResults(
            $searchResult->getResponse()->getData()['hits']['total'] ?? 0
        );

        return $returnReasonPageSearchCollection;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\ReturnReasonPageSearchTransfer
     */
    protected function getMappedReturnReasonPageSearchTransfer(array $data): ReturnReasonPageSearchTransfer
    {
        return (new ReturnReasonPageSearchTransfer())->fromArray($data, true);
    }
}
