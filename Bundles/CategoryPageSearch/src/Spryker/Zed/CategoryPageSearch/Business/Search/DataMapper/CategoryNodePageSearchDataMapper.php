<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper;

use ArrayObject;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NodeTransfer;

class CategoryNodePageSearchDataMapper implements CategoryNodePageSearchDataMapperInterface
{
    protected const TYPE_CATEGORY = 'category';

    protected const KEY_ID_CATEGORY = 'id_category';
    protected const KEY_NAME = 'name';
    protected const KEY_URL = 'url';
    protected const KEY_TYPE = 'type';

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param string $storeName
     * @param string $localeName
     *
     * @return array<string, mixed>
     */
    public function mapNodeTransferToCategoryNodePageSearchDataForStoreAndLocale(
        NodeTransfer $nodeTransfer,
        string $storeName,
        string $localeName
    ): array {
        $categoryLocalizedAttributesTransfer = $this->findCategoryLocalizedAttributesTransferForLocale(
            $nodeTransfer->getCategoryOrFail()->getLocalizedAttributes(),
            $localeName
        );

        return [
            PageIndexMap::IS_ACTIVE => $nodeTransfer->getCategoryOrFail()->getIsActive(),
            PageIndexMap::STORE => $storeName,
            PageIndexMap::LOCALE => $localeName,
            PageIndexMap::TYPE => static::TYPE_CATEGORY,
            PageIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($nodeTransfer, $categoryLocalizedAttributesTransfer),
            PageIndexMap::FULL_TEXT_BOOSTED => $this->getFullTextBoostedData($categoryLocalizedAttributesTransfer),
            PageIndexMap::FULL_TEXT => $this->getFullTextData($categoryLocalizedAttributesTransfer),
            PageIndexMap::SUGGESTION_TERMS => $this->getSuggestionTermsData($categoryLocalizedAttributesTransfer),
            PageIndexMap::COMPLETION_TERMS => $this->getCompletionTermsData($categoryLocalizedAttributesTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null $categoryLocalizedAttributesTransfer
     *
     * @return array<string, mixed>
     */
    protected function getSearchResultData(
        NodeTransfer $nodeTransfer,
        ?CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer = null
    ): array {
        $searchResultData = [
            static::KEY_ID_CATEGORY => $nodeTransfer->getFkCategory(),
            static::KEY_TYPE => static::TYPE_CATEGORY,
        ];

        if (!$categoryLocalizedAttributesTransfer) {
            return $searchResultData;
        }

        $searchResultData[static::KEY_NAME] = $categoryLocalizedAttributesTransfer->getName();
        $searchResultData[static::KEY_URL] = $categoryLocalizedAttributesTransfer->getUrl();

        return $searchResultData;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null $categoryLocalizedAttributesTransfer
     *
     * @return string[]
     */
    protected function getFullTextBoostedData(?CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer): array
    {
        return $categoryLocalizedAttributesTransfer ? [$categoryLocalizedAttributesTransfer->getName()] : [''];
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null $categoryLocalizedAttributesTransfer
     *
     * @return string[]
     */
    protected function getFullTextData(?CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer): array
    {
        if (!$categoryLocalizedAttributesTransfer) {
            return [''];
        }

        return [
            $categoryLocalizedAttributesTransfer->getMetaTitle() ?? '',
            $categoryLocalizedAttributesTransfer->getMetaKeywords() ?? '',
            $categoryLocalizedAttributesTransfer->getMetaDescription() ?? '',
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null $categoryLocalizedAttributesTransfer
     *
     * @return string[]
     */
    protected function getSuggestionTermsData(?CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer): array
    {
        return $categoryLocalizedAttributesTransfer ? [$categoryLocalizedAttributesTransfer->getName()] : [''];
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null $categoryLocalizedAttributesTransfer
     *
     * @return string[]
     */
    protected function getCompletionTermsData(?CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer): array
    {
        return $categoryLocalizedAttributesTransfer ? [$categoryLocalizedAttributesTransfer->getName()] : [''];
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]|\ArrayObject $categoryLocalizedAttributesTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null
     */
    protected function findCategoryLocalizedAttributesTransferForLocale(
        ArrayObject $categoryLocalizedAttributesTransfers,
        string $localeName
    ): ?CategoryLocalizedAttributesTransfer {
        foreach ($categoryLocalizedAttributesTransfers as $categoryLocalizedAttributesTransfer) {
            if ($localeName === $categoryLocalizedAttributesTransfer->getLocale()->getLocaleName()) {
                return $categoryLocalizedAttributesTransfer;
            }
        }

        return null;
    }
}
