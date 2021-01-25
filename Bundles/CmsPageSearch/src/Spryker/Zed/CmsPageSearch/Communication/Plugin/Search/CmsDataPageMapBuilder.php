<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication\Plugin\Search;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;
use Spryker\Zed\Search\Dependency\Plugin\NamedPageMapInterface;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class CmsDataPageMapBuilder implements NamedPageMapInterface
{
    public const COL_URL = 'url';
    public const COL_IS_ACTIVE = 'is_active';
    public const COL_DATA = 'data';
    public const COL_VALID_FROM = 'valid_from';
    public const COL_VALID_TO = 'valid_to';
    public const COL_IS_SEARCHABLE = 'is_searchable';
    public const TYPE_CMS_PAGE = 'cms_page';
    public const TYPE = 'type';
    public const ID_CMS_PAGE = 'id_cms_page';
    public const NAME = 'name';
    protected const COL_NAME = 'name';

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $data, LocaleTransfer $localeTransfer)
    {
        $isActive = $data['is_active'] && $data['is_searchable'];
        $storeName = $data['store_name'] ?? Store::getInstance()->getStoreName();

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore($storeName)
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(static::TYPE_CMS_PAGE)
            ->setIsActive($isActive);

        $this->setActiveInDateRange($data, $pageMapTransfer);

        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, static::ID_CMS_PAGE, $data['id_cms_page'])
            ->addSearchResultData($pageMapTransfer, static::NAME, $data['name'])
            ->addSearchResultData($pageMapTransfer, static::TYPE, static::TYPE_CMS_PAGE)
            ->addSearchResultData($pageMapTransfer, static::COL_URL, $data[static::COL_URL])
            ->addFullTextBoosted($pageMapTransfer, $data['name'])
            ->addFullText($pageMapTransfer, $data['meta_title'])
            ->addFullText($pageMapTransfer, $data['meta_keywords'])
            ->addFullText($pageMapTransfer, $data['meta_description'])
            ->addFullText($pageMapTransfer, implode(',', array_values($data['placeholders'])))
            ->addSuggestionTerms($pageMapTransfer, $data['name'])
            ->addCompletionTerms($pageMapTransfer, $data['name']);

        $pageMapTransfer = $this->addSort($pageMapBuilder, $pageMapTransfer, $data);

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
     * @return string
     */
    public function getName()
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
