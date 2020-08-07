<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\DataMapper;

use Generated\Shared\Search\ReturnReasonIndexMap;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ReturnReasonSearchTransfer;
use Spryker\Shared\SalesReturnSearch\SalesReturnSearchConfig;

class ReturnReasonSearchDataMapper implements ReturnReasonSearchDataMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapReturnReasonDataToSearchData(array $data, LocaleTransfer $localeTransfer): array
    {
        return [
            ReturnReasonIndexMap::TYPE => SalesReturnSearchConfig::RETURN_REASON_RESOURCE_NAME,
            ReturnReasonIndexMap::LOCALE => $localeTransfer->getLocaleName(),
            ReturnReasonIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($data),
            ReturnReasonIndexMap::FULL_TEXT_BOOSTED => $this->getFullTextBoostedData($data),
            ReturnReasonIndexMap::SUGGESTION_TERMS => $this->getSuggestionTermsData($data),
            ReturnReasonIndexMap::COMPLETION_TERMS => $this->getCompletionTermsData($data),
            ReturnReasonIndexMap::STRING_SORT => $this->getStringSortData($data),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getSearchResultData(array $data): array
    {
        return [
            ReturnReasonSearchTransfer::ID_SALES_RETURN_REASON => $data[ReturnReasonSearchTransfer::ID_SALES_RETURN_REASON],
            ReturnReasonSearchTransfer::GLOSSARY_KEY_REASON => $data[ReturnReasonSearchTransfer::GLOSSARY_KEY_REASON],
            ReturnReasonSearchTransfer::NAME => $data[ReturnReasonSearchTransfer::NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return string[]
     */
    protected function getFullTextBoostedData(array $data): array
    {
        return [
            $data[ReturnReasonSearchTransfer::NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return string[]
     */
    protected function getSuggestionTermsData(array $data): array
    {
        return [
            $data[ReturnReasonSearchTransfer::NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return string[]
     */
    protected function getCompletionTermsData(array $data): array
    {
        return [
            $data[ReturnReasonSearchTransfer::NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return string[]
     */
    protected function getStringSortData(array $data): array
    {
        return [
            ReturnReasonSearchTransfer::NAME => $data[ReturnReasonSearchTransfer::NAME],
        ];
    }
}
