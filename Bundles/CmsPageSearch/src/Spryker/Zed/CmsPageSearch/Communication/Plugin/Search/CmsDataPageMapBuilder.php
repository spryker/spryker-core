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

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $cmsPageData, LocaleTransfer $localeTransfer)
    {
        $isActive = $cmsPageData['is_active'] && $cmsPageData['is_searchable'];

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore(Store::getInstance()->getStoreName())
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
}
