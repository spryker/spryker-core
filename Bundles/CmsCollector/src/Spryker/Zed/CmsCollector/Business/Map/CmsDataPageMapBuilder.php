<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Map;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

/**
 * @method \Pyz\Zed\Collector\Communication\CollectorCommunicationFactory getFactory()
 */
class CmsDataPageMapBuilder
{

    const TYPE_CMS_PAGE = 'cms_page';

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $cmsPageData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $cmsPageData, LocaleTransfer $localeTransfer)
    {

        $cmsDataArray = json_decode($cmsPageData['data'], true);
        $localeName = $localeTransfer->getLocaleName();
        $cmsLocalizedAttributes = $cmsDataArray[SpyCmsPageLocalizedAttributesTableMap::TABLE_NAME][$localeName];

        $pageMapTransfer = (new PageMapTransfer())
            ->setStore(Store::getInstance()->getStoreName())
            ->setLocale($localeTransfer->getLocaleName())
            ->setType(static::TYPE_CMS_PAGE);

        /*
         * Here you can hard code which cms data will be used for which search functionality
         */
        $pageMapBuilder
            ->addSearchResultData($pageMapTransfer, 'id_cms_page', $cmsDataArray[SpyCmsPageTableMap::COL_ID_CMS_PAGE])
            ->addSearchResultData($pageMapTransfer, 'name', $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_NAME])
            ->addSearchResultData($pageMapTransfer, 'url', $cmsPageData['url'])
            ->addSearchResultData($pageMapTransfer, 'type', static::TYPE_CMS_PAGE)
            ->addFullTextBoosted($pageMapTransfer, $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_NAME])
            ->addFullText($pageMapTransfer, $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_TITLE])
            ->addFullText($pageMapTransfer, $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_KEYWORDS])
            ->addFullText($pageMapTransfer, $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_DESCRIPTION])
            ->addFullText($pageMapTransfer, $this->extractContents($cmsDataArray[SpyCmsGlossaryKeyMappingTableMap::TABLE_NAME], $localeName))
            ->addSuggestionTerms($pageMapTransfer, $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_NAME])
            ->addCompletionTerms($pageMapTransfer, $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_NAME]);

        return $pageMapTransfer;
    }

    /**
     * @param array $glossaryKeyMappings
     * @param string $localeName
     *
     * @return string
     */
    protected function extractContents(array $glossaryKeyMappings, $localeName)
    {
        $contents = [];
        foreach ($glossaryKeyMappings as $glossaryKeyMapping) {
            $translations = $glossaryKeyMapping[SpyGlossaryKeyTableMap::TABLE_NAME][SpyGlossaryTranslationTableMap::TABLE_NAME];
            if (array_key_exists($localeName, $translations)) {
                $contents[] = $translations[$localeName][SpyGlossaryTranslationTableMap::COL_VALUE];
            }
        }

        return implode(',', $contents);
    }
}
