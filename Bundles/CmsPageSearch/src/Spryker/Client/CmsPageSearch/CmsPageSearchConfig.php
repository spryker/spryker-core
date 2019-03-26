<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\Kernel\AbstractBundleConfig;

class CmsPageSearchConfig extends AbstractBundleConfig
{
    protected const SORT_NAME = 'name';
    protected const SORT_PARAMETER_NAME_ASC = 'name_asc';
    protected const SORT_PARAMETER_NAME_DESC = 'name_desc';

    protected const PAGINATION_DEFAULT_ITEMS_PER_PAGE = 12;
    protected const PAGINATION_VALID_ITEMS_PER_PAGE_OPTIONS = [12, 24, 36];
    protected const PAGINATION_PARAMETER_NAME_PAGE = 'page';
    protected const PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME = 'ipp';

    /**
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function getCmsPagePaginationConfigTransfer(): PaginationConfigTransfer
    {
        return (new PaginationConfigTransfer())
            ->setParameterName(static::PAGINATION_PARAMETER_NAME_PAGE)
            ->setItemsPerPageParameterName(static::PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME)
            ->setDefaultItemsPerPage(static::PAGINATION_DEFAULT_ITEMS_PER_PAGE)
            ->setValidItemsPerPageOptions(static::PAGINATION_VALID_ITEMS_PER_PAGE_OPTIONS);
    }

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getAscendingNameSortConfigTransfer(): SortConfigTransfer
    {
        return (new SortConfigTransfer())
            ->setName(static::SORT_NAME)
            ->setParameterName(static::SORT_PARAMETER_NAME_ASC)
            ->setFieldName(PageIndexMap::STRING_SORT)
            ->setIsDescending(false);
    }

    /**
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function getDescendingNameSortConfigTransfer(): SortConfigTransfer
    {
        return (new SortConfigTransfer())
            ->setName(static::SORT_NAME)
            ->setParameterName(static::SORT_PARAMETER_NAME_DESC)
            ->setFieldName(PageIndexMap::STRING_SORT)
            ->setIsDescending(true);
    }
}
