<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search\DataMapper;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Store;

class CategoryNodePageSearchDataMapper implements CategoryNodePageSearchDataMapperInterface
{
    protected const TYPE_CATEGORY = 'category';

    protected const KEY_SPY_CATEGORY = 'spy_category';
    protected const KEY_SPY_CATEGORY_ATTRIBUTES = 'spy_category_attributes';
    protected const KEY_IS_ACTIVE = 'is_active';
    protected const KEY_IS_SEARCHABLE = 'is_searchable';
    protected const KEY_ID_CATEGORY = 'id_category';
    protected const KEY_NAME = 'name';
    protected const KEY_SPY_URLS = 'spy_urls';
    protected const KEY_URL = 'url';
    protected const KEY_FK_CATEGORY = 'fk_category';
    protected const KEY_TYPE = 'type';
    protected const KEY_META_TITLE = 'meta_title';
    protected const KEY_META_KEYWORDS = 'meta_keywords';
    protected const KEY_META_DESCRIPTION = 'meta_description';

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapCategoryNodeDataToSearchData(array $data, LocaleTransfer $localeTransfer): array
    {
        return [
            PageIndexMap::IS_ACTIVE => $data[static::KEY_SPY_CATEGORY][static::KEY_IS_ACTIVE] && $data[static::KEY_SPY_CATEGORY][static::KEY_IS_SEARCHABLE],
            PageIndexMap::STORE => Store::getInstance()->getStoreName(),
            PageIndexMap::LOCALE => $localeTransfer->getLocaleName(),
            PageIndexMap::TYPE => static::TYPE_CATEGORY,
            PageIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($data),
            PageIndexMap::FULL_TEXT_BOOSTED => $this->getFullTextBoostedData($data),
            PageIndexMap::FULL_TEXT => $this->getFullTextData($data),
            PageIndexMap::SUGGESTION_TERMS => $this->getSuggestionTermsData($data),
            PageIndexMap::COMPLETION_TERMS => $this->getCompletionTermsData($data),
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
            static::KEY_ID_CATEGORY => $data[static::KEY_FK_CATEGORY],
            static::KEY_NAME => $this->getCategoryAttribute($data)[static::KEY_NAME],
            static::KEY_URL => $data[static::KEY_SPY_URLS][0][static::KEY_URL],
            static::KEY_TYPE => static::TYPE_CATEGORY,
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getFullTextBoostedData(array $data): array
    {
        return [
            $this->getCategoryAttribute($data)[static::KEY_NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getFullTextData(array $data): array
    {
        $categoryAttribute = $this->getCategoryAttribute($data);

        return [
            $categoryAttribute[static::KEY_META_TITLE] ?? '',
            $categoryAttribute[static::KEY_META_KEYWORDS] ?? '',
            $categoryAttribute[static::KEY_META_DESCRIPTION] ?? '',
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getSuggestionTermsData(array $data): array
    {
        return [
            $this->getCategoryAttribute($data)[static::KEY_NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getCompletionTermsData(array $data): array
    {
        return [
            $this->getCategoryAttribute($data)[static::KEY_NAME],
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getCategoryAttribute(array $data): array
    {
        return reset($data[static::KEY_SPY_CATEGORY][static::KEY_SPY_CATEGORY_ATTRIBUTES]);
    }
}
