<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\DataMapper;

use Generated\Shared\Search\MerchantIndexMap;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\MerchantSearch\MerchantSearchConfig;

class MerchantSearchDataMapper implements MerchantSearchDataMapperInterface
{
    /**
     * @param array $data
     *
     * @return array
     */
    public function mapMerchantDataToSearchData(array $data): array
    {
        return [
            MerchantIndexMap::TYPE => MerchantSearchConfig::MERCHANT_RESOURCE_NAME,
            MerchantIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($data),
            MerchantIndexMap::FULL_TEXT_BOOSTED => $this->getFullTextBoostedData($data),
            MerchantIndexMap::SUGGESTION_TERMS => $this->getSuggestionTermsData($data),
            MerchantIndexMap::COMPLETION_TERMS => $this->getCompletionTermsData($data),
            MerchantIndexMap::STRING_SORT => $this->getStringSortData($data),
            MerchantIndexMap::STORE => $this->getStoresData($data),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getSearchResultData(array $data): array
    {
        return (new MerchantSearchTransfer())->fromArray($data, true)->toArray();
    }

    /**
     * @param string[] $data
     *
     * @return string[]
     */
    protected function getFullTextBoostedData(array $data): array
    {
        return [
            $data[MerchantSearchTransfer::NAME],
        ];
    }

    /**
     * @param string[] $data
     *
     * @return string[]
     */
    protected function getSuggestionTermsData(array $data): array
    {
        return [
            $data[MerchantSearchTransfer::NAME],
        ];
    }

    /**
     * @param string[] $data
     *
     * @return string[]
     */
    protected function getCompletionTermsData(array $data): array
    {
        return [
            $data[MerchantSearchTransfer::NAME],
        ];
    }

    /**
     * @param string[] $data
     *
     * @return string[]
     */
    protected function getStringSortData(array $data): array
    {
        return [
            MerchantSearchTransfer::NAME => $data[MerchantSearchTransfer::NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getStoresData(array $data): array
    {
        return array_column($data[MerchantTransfer::STORE_RELATION][StoreRelationTransfer::STORES], StoreTransfer::NAME);
    }
}
