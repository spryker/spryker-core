<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Helper;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsVersionDataHelper implements CmsVersionDataHelperInterface
{

    /**
     * @var CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * CmsVersionDataHelper constructor.
     *
     * @param CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsGuiToCmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsPageTransfer
     */
    public function extractCmsPageTransfer(CmsVersionTransfer $cmsVersionTransfer)
    {
        $versionDataArray = json_decode($cmsVersionTransfer->getData(), true);
        $cmsPageTransfer = $this->createCmsPageTransfer($versionDataArray);

        foreach ($versionDataArray[SpyCmsPageLocalizedAttributesTableMap::TABLE_NAME] as $localeName => $item) {
            $cmsPageAttributeTransfer = $this->extractCmsPageAttributeTransfer(
                $localeName,
                $item[SpyCmsPageLocalizedAttributesTableMap::COL_NAME],
                $versionDataArray[SpyCmsPageTableMap::COL_ID_CMS_PAGE]
            );
            $cmsPageTransfer->addPageAttribute($cmsPageAttributeTransfer);

            $cmsPageMetaAttributeTransfer = $this->extractCmsMetaAttributeTransfer($item, $localeName);
            $cmsPageTransfer->addMetaAttribute($cmsPageMetaAttributeTransfer);
        }

        return $cmsPageTransfer;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsGlossaryTransfer
     */
    public function extractCmsGlossaryPageTransfer(CmsVersionTransfer $cmsVersionTransfer)
    {
        $versionDataArray = json_decode($cmsVersionTransfer->getData(), true);
        $cmsGlossaryTransfer = new CmsGlossaryTransfer();

        foreach ($versionDataArray[SpyCmsGlossaryKeyMappingTableMap::TABLE_NAME] as $item) {
            $cmsGlossaryAttributesTransfer = new CmsGlossaryAttributesTransfer();
            $cmsGlossaryAttributesTransfer->setPlaceholder($item[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER]);
            $cmsGlossaryAttributesTransfer->setTranslationKey($item[SpyGlossaryKeyTableMap::TABLE_NAME][SpyGlossaryKeyTableMap::COL_KEY]);
            $this->extractTranslations($cmsGlossaryAttributesTransfer, $item[SpyGlossaryKeyTableMap::TABLE_NAME][SpyGlossaryTranslationTableMap::TABLE_NAME]);
            $cmsGlossaryTransfer->addGlossaryAttribute($cmsGlossaryAttributesTransfer);
        }

        return $cmsGlossaryTransfer;
    }

    /**
     * @param string $localeName
     * @param string $pageName
     * @param int $idCmsPage
     *
     * @return CmsPageAttributesTransfer
     */
    protected function extractCmsPageAttributeTransfer($localeName, $pageName, $idCmsPage)
    {
        $urlEntity = $this->cmsQueryContainer->queryPageWithUrlByIdCmsPageAndLocaleName($idCmsPage, $localeName)->findOne();

        $cmsPageAttributeTransfer = new CmsPageAttributesTransfer();
        $cmsPageAttributeTransfer->setLocaleName($localeName);
        $cmsPageAttributeTransfer->setName($pageName);
        $cmsPageAttributeTransfer->setUrl($urlEntity->getUrl());

        return $cmsPageAttributeTransfer;
    }

    /**
     * @param array $item
     * @param string $localeName
     *
     * @return CmsPageMetaAttributesTransfer
     */
    protected function extractCmsMetaAttributeTransfer(array $item, $localeName)
    {
        $cmsPageMetaAttributeTransfer = new CmsPageMetaAttributesTransfer();
        $cmsPageMetaAttributeTransfer->setLocaleName($localeName);
        $cmsPageMetaAttributeTransfer->setMetaTitle($item[SpyCmsPageLocalizedAttributesTableMap::COL_META_TITLE]);
        $cmsPageMetaAttributeTransfer->setMetaDescription($item[SpyCmsPageLocalizedAttributesTableMap::COL_META_DESCRIPTION]);
        $cmsPageMetaAttributeTransfer->setMetaKeywords($item[SpyCmsPageLocalizedAttributesTableMap::COL_META_KEYWORDS]);

        return $cmsPageMetaAttributeTransfer;
    }

    /**
     * @param CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer
     * @param array $translations
     *
     * @return void
     */
    protected function extractTranslations(CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer, array $translations)
    {
        foreach ($translations as $localeName => $translation) {
            $cmsPlaceholderTranslationTransfer = new CmsPlaceholderTranslationTransfer();
            $cmsPlaceholderTranslationTransfer->setLocaleName($localeName);
            $cmsPlaceholderTranslationTransfer->setTranslation($translation[SpyGlossaryTranslationTableMap::COL_VALUE]);
            $cmsGlossaryAttributesTransfer->addTranslation($cmsPlaceholderTranslationTransfer);
        }
    }

    /**
     * @param array $versionDataArray
     *
     * @return CmsPageTransfer
     */
    protected function createCmsPageTransfer(array $versionDataArray)
    {
        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->setFkPage($versionDataArray[SpyCmsPageTableMap::COL_ID_CMS_PAGE]);
        $cmsPageTransfer->setFkTemplate($versionDataArray[SpyCmsPageTableMap::COL_FK_TEMPLATE]);
        $cmsPageTransfer->setValidFrom($versionDataArray[SpyCmsPageTableMap::COL_VALID_FROM]);
        $cmsPageTransfer->setValidTo($versionDataArray[SpyCmsPageTableMap::COL_VALID_TO]);
        $cmsPageTransfer->setIsSearchable($versionDataArray[SpyCmsPageTableMap::COL_IS_SEARCHABLE]);
        $cmsPageTransfer->setIsActive($versionDataArray[SpyCmsPageTableMap::COL_IS_ACTIVE]);
        $cmsPageTransfer->setTemplateName($versionDataArray[SpyCmsTemplateTableMap::TABLE_NAME][SpyCmsTemplateTableMap::COL_TEMPLATE_NAME]);

        return $cmsPageTransfer;
    }
}
