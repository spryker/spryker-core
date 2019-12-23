<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Business\Search\DataMapper;

use DateTime;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\Kernel\Store;

class CmsPageSearchDataMapper implements CmsPageSearchDataMapperInterface
{
    protected const TYPE_CMS_PAGE = 'cms_page';

    protected const KEY_URL = 'url';
    protected const KEY_VALID_FROM = 'valid_from';
    protected const KEY_VALID_TO = 'valid_to';
    protected const KEY_TYPE = 'type';
    protected const KEY_ID_CMS_PAGE = 'id_cms_page';
    protected const KEY_NAME = 'name';
    protected const KEY_IS_SEARCHABLE = 'is_searchable';
    protected const KEY_IS_ACTIVE = 'is_active';
    protected const KEY_STORE_NAME = 'store_name';
    protected const KEY_META_TITLE = 'meta_title';
    protected const KEY_META_KEYWORDS = 'meta_keywords';
    protected const KEY_META_DESCRIPTION = 'meta_description';
    protected const KEY_PLACEHOLDERS = 'placeholders';

    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapCmsDataToSearchData(array $data, LocaleTransfer $localeTransfer): array
    {
        $isActive = $data[static::KEY_IS_ACTIVE] && $data[static::KEY_IS_SEARCHABLE];
        $storeName = $data[static::KEY_STORE_NAME] ?? Store::getInstance()->getStoreName();

        return [
            PageIndexMap::IS_ACTIVE => $isActive,
            PageIndexMap::STORE => $storeName,
            PageIndexMap::LOCALE => $localeTransfer->getLocaleName(),
            PageIndexMap::TYPE => static::TYPE_CMS_PAGE,
            PageIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($data),
            PageIndexMap::ACTIVE_FROM => $this->getActiveFrom($data),
            PageIndexMap::ACTIVE_TO => $this->getActiveTo($data),
            PageIndexMap::FULL_TEXT_BOOSTED => [
                $data[static::KEY_NAME],
            ],
            PageIndexMap::FULL_TEXT => [
                $data[static::KEY_META_TITLE],
                $data[static::KEY_META_KEYWORDS],
                $data[static::KEY_META_DESCRIPTION],
                implode(',', array_values($data[static::KEY_PLACEHOLDERS])),
            ],
            PageIndexMap::SUGGESTION_TERMS => [
                $data[static::KEY_NAME],
            ],
            PageIndexMap::COMPLETION_TERMS => [
                $data[static::KEY_NAME],
            ],
            PageIndexMap::STRING_SORT => [
                static::KEY_NAME => $data[static::KEY_NAME],
            ],
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
            static::KEY_ID_CMS_PAGE => $data[static::KEY_ID_CMS_PAGE],
            static::KEY_NAME => $data[static::KEY_NAME],
            static::KEY_TYPE => static::TYPE_CMS_PAGE,
            static::KEY_URL => $data[static::KEY_URL],
        ];
    }

    /**
     * @param array $data
     *
     * @return string|null
     */
    protected function getActiveFrom(array $data): ?string
    {
        return isset($data[static::KEY_VALID_FROM]) ? (new DateTime($data[static::KEY_VALID_FROM]))->format(static::DATE_FORMAT) : null;
    }

    /**
     * @param array $data
     *
     * @return string|null
     */
    protected function getActiveTo(array $data): ?string
    {
        return isset($data[static::KEY_VALID_TO]) ? (new DateTime($data[static::KEY_VALID_TO]))->format(static::DATE_FORMAT) : null;
    }
}
