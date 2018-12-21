<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\DataSet;

interface CmsPageDataSet
{
    public const KEY_TEMPLATE_NAME = 'template_name';
    public const KEY_PAGE_KEY = 'page_key';
    public const KEY_IS_ACTIVE = 'is_active';
    public const KEY_IS_SEARCHABLE = 'is_searchable';
    public const KEY_NAME = 'name';
    public const KEY_META_TITLE = 'meta_title';
    public const KEY_META_DESCRIPTION = 'meta_description';
    public const KEY_META_KEYWORDS = 'meta_keywords';
    public const KEY_URL = 'url';
    public const KEY_PUBLISH = 'publish';

    public const KEY_PLACEHOLDER = 'placeholder';
    public const KEY_PLACEHOLDER_TITLE = 'placeholder.title';
    public const KEY_PLACEHOLDER_CONTENT = 'placeholder.content';
}
