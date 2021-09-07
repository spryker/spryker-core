<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\DataSet;

interface CmsPageStoreDataSet
{
    /**
     * @var string
     */
    public const KEY_PAGE_NAME = 'page_key';
    /**
     * @var string
     */
    public const KEY_STORE_NAME = 'store_name';

    /**
     * @var string
     */
    public const ID_STORE = 'id_store';
    /**
     * @var string
     */
    public const ID_CMS_PAGE = 'id_cms_page';
}
