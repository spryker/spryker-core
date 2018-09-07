<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsPageSearch;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\SortConfigTransfer;
use Spryker\Client\CmsPageSearch\Config\PaginationConfigBuilderInterface;
use Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface;
use Spryker\Client\Kernel\AbstractBundleConfig;

class CmsPageSearchConfig extends AbstractBundleConfig
{
    protected const CMS_PAGES_SORT_OPTIONS = [
        ['name' => 'name', 'parameterName' => 'name_asc', 'fieldName' => PageIndexMap::STRING_SORT, 'isDescending' => false],
        ['name' => 'name', 'parameterName' => 'name_desc', 'fieldName' => PageIndexMap::STRING_SORT, 'isDescending' => true],
    ];

    protected const CMS_PAGES_PAGINATION_DEFAULT_ITEMS_PER_PAGE = 12;
    protected const CMS_PAGES_PAGINATION_VALID_ITEMS_PER_PAGE_OPTIONS = [12, 24, 36];
    protected const CMS_PAGES_PAGINATION_PARAMETER_NAME_PAGE = 'page';
    protected const CMS_PAGES_PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME = 'ipp';

    /**
     * @param \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface $sortConfigBuilder
     *
     * @return \Spryker\Client\CmsPageSearch\Config\SortConfigBuilderInterface
     */
    public function buildCmsPageSortConfig(SortConfigBuilderInterface $sortConfigBuilder): SortConfigBuilderInterface
    {
        $sortingOptions = $this->getCmsPageSortOptions();

        foreach ($sortingOptions as $option) {
            $sortConfigTransfer = (new SortConfigTransfer())
                ->setName($option['name'])
                ->setParameterName($option['parameterName'])
                ->setFieldName($option['fieldName'])
                ->setIsDescending($option['isDescending']);
            $sortConfigBuilder->addSort($sortConfigTransfer);
        }

        return $sortConfigBuilder;
    }

    /**
     * @return array
     */
    public function getCmsPageSortOptions(): array
    {
        return static::CMS_PAGES_SORT_OPTIONS;
    }

    /**
     * @param \Spryker\Client\CmsPageSearch\Config\PaginationConfigBuilderInterface $paginationConfigBuilder
     *
     * @return \Spryker\Client\CmsPageSearch\Config\PaginationConfigBuilderInterface
     */
    public function buildCmsPagePaginationConfig(PaginationConfigBuilderInterface $paginationConfigBuilder): PaginationConfigBuilderInterface
    {
        $paginationConfigTransfer = (new PaginationConfigTransfer())
            ->setParameterName($this->getCmsPagePaginationParameterNamePage())
            ->setItemsPerPageParameterName($this->getCmsPagePaginationItemsPerPageParameterName())
            ->setDefaultItemsPerPage($this->getCmsPagePaginationDefaultItemsPerPage())
            ->setValidItemsPerPageOptions($this->getCmsPagePaginationValidItemsPerPageOptions());

        $paginationConfigBuilder->setPagination($paginationConfigTransfer);

        return $paginationConfigBuilder;
    }

    /**
     * @return string
     */
    public function getCmsPagePaginationParameterNamePage(): string
    {
        return static::CMS_PAGES_PAGINATION_PARAMETER_NAME_PAGE;
    }

    /**
     * @return string
     */
    public function getCmsPagePaginationItemsPerPageParameterName(): string
    {
        return static::CMS_PAGES_PAGINATION_ITEMS_PER_PAGE_PARAMETER_NAME;
    }

    /**
     * @return int
     */
    public function getCmsPagePaginationDefaultItemsPerPage(): int
    {
        return static::CMS_PAGES_PAGINATION_DEFAULT_ITEMS_PER_PAGE;
    }

    /**
     * @return array
     */
    public function getCmsPagePaginationValidItemsPerPageOptions(): array
    {
        return static::CMS_PAGES_PAGINATION_VALID_ITEMS_PER_PAGE_OPTIONS;
    }
}
