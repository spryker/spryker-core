<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\DataSet;

interface CmsPageDataSet
{
    /**
     * @var string
     */
    public const KEY_TEMPLATE_NAME = 'template_name';
    /**
     * @var string
     */
    public const KEY_PAGE_KEY = 'page_key';
    /**
     * @var string
     */
    public const KEY_IS_ACTIVE = 'is_active';
    /**
     * @var string
     */
    public const KEY_IS_SEARCHABLE = 'is_searchable';
    /**
     * @var string
     */
    public const KEY_NAME = 'name';
    /**
     * @var string
     */
    public const KEY_META_TITLE = 'meta_title';
    /**
     * @var string
     */
    public const KEY_META_DESCRIPTION = 'meta_description';
    /**
     * @var string
     */
    public const KEY_META_KEYWORDS = 'meta_keywords';
    /**
     * @var string
     */
    public const KEY_URL = 'url';
    /**
     * @var string
     */
    public const KEY_PUBLISH = 'publish';

    /**
     * @var string
     */
    public const KEY_PLACEHOLDER = 'placeholder';
    /**
     * @var string
     */
    public const KEY_PLACEHOLDER_TITLE = 'placeholder.title';
    /**
     * @var string
     */
    public const KEY_PLACEHOLDER_CONTENT = 'placeholder.content';
}
