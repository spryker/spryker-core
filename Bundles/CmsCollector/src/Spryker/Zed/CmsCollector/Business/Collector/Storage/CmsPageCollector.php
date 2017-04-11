<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Collector\Storage;

use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;

class CmsPageCollector extends AbstractStoragePropelCollector
{

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $cmsDataArray = json_decode($collectItemData['data'], true);
        $localeName = $this->locale->getLocaleName();
        $cmsLocalizedAttributes = $cmsDataArray[SpyCmsPageLocalizedAttributesTableMap::TABLE_NAME][$localeName];

        return [
            'url' => $collectItemData['url'],
            'valid_from' => $cmsDataArray[SpyCmsPageTableMap::COL_VALID_FROM],
            'valid_to' => $cmsDataArray[SpyCmsPageTableMap::COL_VALID_TO],
            'is_active' => $cmsDataArray[SpyCmsPageTableMap::COL_IS_ACTIVE],
            'id' => $cmsDataArray[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            'template' => $cmsDataArray[SpyCmsTemplateTableMap::TABLE_NAME][SpyCmsTemplateTableMap::COL_TEMPLATE_PATH],
            'placeholders' => $this->extractPlaceholders($cmsDataArray[SpyCmsGlossaryKeyMappingTableMap::TABLE_NAME], $localeName),
            'name' => $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_NAME],
            'meta_title' => $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_TITLE],
            'meta_keywords' => $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_KEYWORDS],
            'meta_description' => $cmsLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_DESCRIPTION],
        ];
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return CmsConstants::RESOURCE_TYPE_PAGE;
    }

    /**
     * @param array $glossaryKeyMappings
     * @param string $localeName
     *
     * @return array
     */
    protected function extractPlaceholders($glossaryKeyMappings, $localeName)
    {
        $placeholders = [];
        foreach ($glossaryKeyMappings as $glossaryKeyMapping) {
            $placeholder = $glossaryKeyMapping[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER];
            $placeholders[$placeholder] = $glossaryKeyMapping[SpyGlossaryKeyTableMap::TABLE_NAME][SpyGlossaryTranslationTableMap::TABLE_NAME][$localeName][SpyGlossaryTranslationTableMap::COL_VALUE];
        }

        return $placeholders;
    }

}
