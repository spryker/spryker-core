<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Client\SelfServicePortal\Search\ResultFormatter;

use ArrayObject;
use Elastica\Result;
use Elastica\ResultSet;
use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchTransfer;

class SspAssetSearchResultFormatter implements SspAssetSearchResultFormatterInterface
{
    /**
     * @var string
     */
    protected const SEARCH_RESULT_DATA_KEY = 'search-result-data';

    /**
     * @var string
     */
    protected const SEARCH_DATA_NAME_KEY = 'name';

    /**
     * @var string
     */
    protected const SEARCH_DATA_SERIAL_NUMBER_KEY = 'serial_number';

    /**
     * @var string
     */
    protected const SEARCH_DATA_REFERENCE_KEY = 'reference';

    /**
     * @param \Elastica\ResultSet|mixed $searchResult
     * @param array<string, mixed> $requestParameters
     *
     * @return \Generated\Shared\Transfer\SspAssetSearchCollectionTransfer
     */
    public function formatResult($searchResult, array $requestParameters = []): SspAssetSearchCollectionTransfer
    {
        if (!$searchResult instanceof ResultSet) {
            return new SspAssetSearchCollectionTransfer();
        }

        $collection = new SspAssetSearchCollectionTransfer();
        $sspAssetSearchTransfers = new ArrayObject();

        foreach ($searchResult->getResults() as $result) {
            $sspAssetSearchTransfer = $this->mapResultToTransfer($result);
            $sspAssetSearchTransfers->append($sspAssetSearchTransfer);
        }

        $collection->setSspAssets($sspAssetSearchTransfers);

        return $collection;
    }

    protected function mapResultToTransfer(Result $result): SspAssetSearchTransfer
    {
        $sspAssetTransfer = new SspAssetSearchTransfer();
        $source = $result->getSource();

        $sspAssetTransfer->setIdSspAsset((int)$result->getId());

        if (!isset($source[static::SEARCH_RESULT_DATA_KEY])) {
            return $sspAssetTransfer;
        }

        $searchData = $source[static::SEARCH_RESULT_DATA_KEY];
        $sspAssetTransfer->setName($searchData[static::SEARCH_DATA_NAME_KEY] ?? '');
        $sspAssetTransfer->setSerialNumber($searchData[static::SEARCH_DATA_SERIAL_NUMBER_KEY] ?? '');
        $sspAssetTransfer->setReference($searchData[static::SEARCH_DATA_REFERENCE_KEY] ?? '');

        return $sspAssetTransfer;
    }
}
