<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ServicePointSearch\Plugin\Elasticsearch\ResultFormatter;

use Elastica\ResultSet;
use Generated\Shared\Search\ServicePointIndexMap;
use Generated\Shared\Transfer\ServicePointSearchCollectionTransfer;
use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\AbstractElasticsearchResultFormatterPlugin;

class ServicePointSearchResultFormatterPlugin extends AbstractElasticsearchResultFormatterPlugin
{
    /**
     * @var string
     */
    protected const NAME = 'ServicePointSearchCollection';

    /**
     * @var string
     */
    protected const ITEMS_PER_PAGE = 'from';

    /**
     * {@inheritDoc}
     *
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
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer
     */
    protected function formatSearchResult(ResultSet $searchResult, array $requestParameters): ServicePointSearchCollectionTransfer
    {
        $servicePointSearchCollectionTransfer = new ServicePointSearchCollectionTransfer();

        foreach ($searchResult->getResults() as $document) {
            $servicePointSearchCollectionTransfer->addServicePoint(
                $this->getMappedServicePointSearchTransfer($document->getSource()[ServicePointIndexMap::SEARCH_RESULT_DATA]),
            );
        }

        $servicePointSearchCollectionTransfer = $this->expandServicePointSearchCollectionTransferWithItemsPerPage(
            $searchResult,
            $servicePointSearchCollectionTransfer,
        );

        return $servicePointSearchCollectionTransfer->setNbResults($searchResult->getTotalHits());
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchTransfer
     */
    protected function getMappedServicePointSearchTransfer(array $data): ServicePointSearchTransfer
    {
        return (new ServicePointSearchTransfer())->fromArray($data, true);
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     * @param \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer $servicePointSearchCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointSearchCollectionTransfer
     */
    protected function expandServicePointSearchCollectionTransferWithItemsPerPage(
        ResultSet $searchResult,
        ServicePointSearchCollectionTransfer $servicePointSearchCollectionTransfer
    ): ServicePointSearchCollectionTransfer {
        $query = $searchResult->getQuery();
        if (!$query->hasParam(static::ITEMS_PER_PAGE)) {
            return $servicePointSearchCollectionTransfer;
        }

        return $servicePointSearchCollectionTransfer->setItemsPerPage(
            (int)$query->getParam(static::ITEMS_PER_PAGE),
        );
    }
}
