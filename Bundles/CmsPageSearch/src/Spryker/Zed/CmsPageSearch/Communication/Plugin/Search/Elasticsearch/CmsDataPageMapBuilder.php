<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication\Plugin\Search\Elasticsearch;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\PageMapPluginInterface;

/**
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class CmsDataPageMapBuilder implements PageMapPluginInterface
{
    protected const COL_URL = 'url';
    protected const COL_VALID_FROM = 'valid_from';
    protected const COL_VALID_TO = 'valid_to';
    protected const TYPE_CMS_PAGE = 'cms_page';
    protected const TYPE = 'type';
    protected const ID_CMS_PAGE = 'id_cms_page';
    protected const NAME = 'name';
    protected const COL_NAME = 'name';

    /**
     * @param \Spryker\Zed\SearchElasticsearchExtension\Business\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $cmsPageData, LocaleTransfer $localeTransfer): PageMapTransfer
    {
        $isActive = $cmsPageData['is_active'] && $cmsPageData['is_searchable'];
        $storeName = $cmsPageData['store_name'] ?? Store::getInstance()->getStoreName();

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore($storeName)
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(static::TYPE_CMS_PAGE)
            ->setIsActive($isActive);

        $this->setActiveInDateRange($cmsPageData, $pageMapTransfer);

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, static::ID_CMS_PAGE, $cmsPageData['id_cms_page'])
            ->addSearchResultData($pageMapTransfer, static::NAME, $cmsPageData['name'])
            ->addSearchResultData($pageMapTransfer, static::TYPE, static::TYPE_CMS_PAGE)
            ->addSearchResultData($pageMapTransfer, static::COL_URL, $cmsPageData[static::COL_URL])
            ->addFullTextBoosted($pageMapTransfer, $cmsPageData['name'])
            ->addFullText($pageMapTransfer, $cmsPageData['meta_title'])
            ->addFullText($pageMapTransfer, $cmsPageData['meta_keywords'])
            ->addFullText($pageMapTransfer, $cmsPageData['meta_description'])
            ->addFullText($pageMapTransfer, implode(',', array_values($cmsPageData['placeholders'])))
            ->addSuggestionTerms($pageMapTransfer, $cmsPageData['name'])
            ->addCompletionTerms($pageMapTransfer, $cmsPageData['name']);

        $pageMapTransfer = $this->addSort($pageMapBuilder, $pageMapTransfer, $cmsPageData);

        return $pageMapTransfer;
    }

    /**
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     *
     * @return void
     */
    protected function setActiveInDateRange(array $cmsPageData, PageMapTransfer $pageMapTransfer)
    {
        if ($cmsPageData[static::COL_VALID_FROM]) {
            $pageMapTransfer->setActiveFrom(
                (new DateTime($cmsPageData[static::COL_VALID_FROM]))->format('Y-m-d')
            );
        }

        if ($cmsPageData[static::COL_VALID_TO]) {
            $pageMapTransfer->setActiveTo(
                (new DateTime($cmsPageData[static::COL_VALID_TO]))->format('Y-m-d')
            );
        }
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return CmsPageSearchConstants::CMS_PAGE_RESOURCE_NAME;
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $cmsPageData
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    protected function addSort(PageMapBuilderInterface $pageMapBuilder, PageMapTransfer $pageMapTransfer, array $cmsPageData): PageMapTransfer
    {
        $pageMapBuilder->addStringSort($pageMapTransfer, static::COL_NAME, $cmsPageData['name']);

        return $pageMapTransfer;
    }
}
