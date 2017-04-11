<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Handler;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsGlossaryKeyMappingMigrationHandler implements MigrationHandlerInterface
{
    /**
     * @var CmsGlossarySaverInterface
     */
    protected $cmsGlossarySaver;

    /**
     * @var CmsToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsGlossarySaverInterface $cmsGlossarySaver
     * @param CmsToLocaleInterface $localeFacade
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsGlossarySaverInterface $cmsGlossarySaver, CmsToLocaleInterface $localeFacade, CmsQueryContainerInterface $queryContainer)
    {
        $this->cmsGlossarySaver = $cmsGlossarySaver;
        $this->localeFacade = $localeFacade;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param array $originData
     * @param array $targetData
     *
     * @return void
     */
    public function handle(array $originData, array $targetData)
    {
        $this->deleteOriginGlossaryKeyMappings(
            $originData[SpyCmsPageTableMap::COL_ID_CMS_PAGE]
        );

        $this->createTargetGlossaryKeyMappings(
            $targetData[SpyCmsGlossaryKeyMappingTableMap::TABLE_NAME],
            $originData[SpyCmsPageTableMap::COL_ID_CMS_PAGE],
            $targetData[SpyCmsTemplateTableMap::TABLE_NAME][SpyCmsTemplateTableMap::COL_TEMPLATE_NAME]
        );
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    protected function deleteOriginGlossaryKeyMappings($idCmsPage)
    {
        $idGlossaryKeys = $this->queryContainer->queryGlossaryKeyMappingsByPageId($idCmsPage)
            ->select(SpyCmsGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY)
            ->find()
            ->toArray();

        if (empty($idGlossaryKeys)) {
            return;
        }

        $this->queryContainer->queryGlossaryKeyMappingsByFkGlossaryKeys($idGlossaryKeys)->delete();
        $this->queryContainer->queryGlossaryTranslationByFkGlossaryKeys($idGlossaryKeys)->delete();
        $this->queryContainer->queryGlossaryKeyByIdGlossaryKeys($idGlossaryKeys)->delete();
    }

    /**
     * @param array $glossaryKeyMappings
     * @param int $idCmsPage
     * @param string $templateName
     *
     * @return void
     */
    protected function createTargetGlossaryKeyMappings(array $glossaryKeyMappings, $idCmsPage, $templateName)
    {
        $glossaryAttributeTransfers = $this->createCmsGlossaryAttributeTransfers($glossaryKeyMappings, $idCmsPage, $templateName);
        $this->cmsGlossarySaver->saveCmsGlossary((new CmsGlossaryTransfer())->setGlossaryAttributes($glossaryAttributeTransfers));
    }

    /**
     * @param $glossaryKeyMappings
     * @param $idCmsPage
     * @param string $templateName
     *
     * @return \ArrayObject
     */
    protected function createCmsGlossaryAttributeTransfers($glossaryKeyMappings, $idCmsPage, $templateName)
    {
        $glossaryAttributeTransfers = new \ArrayObject();
        foreach ($glossaryKeyMappings as $glossaryKeyMapping) {
            $translations = $this->createCmsPlaceholderTranslationTransfers(
                $glossaryKeyMapping[SpyGlossaryKeyTableMap::TABLE_NAME][SpyGlossaryTranslationTableMap::TABLE_NAME]
            );

            $glossaryAttributeTransfers[] = $this->createGlossaryAttributeTransfer(
                $translations,
                $idCmsPage,
                $glossaryKeyMapping[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER],
                $templateName
            );
        }

        return $glossaryAttributeTransfers;
    }

    /**
     * @param \ArrayObject $translations
     * @param int $idCmsPage
     * @param string $placeholder
     * @param string $templateName
     *
     * @return CmsGlossaryAttributesTransfer
     */
    protected function createGlossaryAttributeTransfer(\ArrayObject $translations, $idCmsPage, $placeholder, $templateName)
    {
        $glossaryAttributeTransfer = new CmsGlossaryAttributesTransfer();
        $glossaryAttributeTransfer->setPlaceholder($placeholder);
        $glossaryAttributeTransfer->setFkPage($idCmsPage);
        $glossaryAttributeTransfer->setTemplateName($templateName);
        $glossaryAttributeTransfer->setTranslations($translations);

        return $glossaryAttributeTransfer;
    }

    /**
     * @param array $translations
     *
     * @return \ArrayObject
     */
    protected function createCmsPlaceholderTranslationTransfers(array $translations)
    {
        $newTranslation = new \ArrayObject();
        foreach ($translations as $localeName => $translation) {
            $localeTransfer = $this->localeFacade->getLocale($localeName);
            $newTranslation[] = $this->createCmsPlaceholderTranslationTransfer(
                $translation[SpyGlossaryTranslationTableMap::COL_VALUE],
                $localeTransfer
            );
        }

        return $newTranslation;
    }

    /**
     * @param string $value
     * @param LocaleTransfer $localeTransfer
     *
     * @return CmsPlaceholderTranslationTransfer
     */
    protected function createCmsPlaceholderTranslationTransfer($value, LocaleTransfer $localeTransfer)
    {
        $cmsPlaceholderTranslation = new CmsPlaceholderTranslationTransfer();
        $cmsPlaceholderTranslation->setTranslation($value);
        $cmsPlaceholderTranslation->setFkLocale($localeTransfer->getIdLocale());
        $cmsPlaceholderTranslation->setLocaleName($localeTransfer->getLocaleName());

        return $cmsPlaceholderTranslation;
    }

}
