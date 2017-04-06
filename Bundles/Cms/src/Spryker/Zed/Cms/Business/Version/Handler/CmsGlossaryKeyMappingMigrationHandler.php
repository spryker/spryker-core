<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Handler;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface;
use Spryker\Zed\Cms\Business\Version\Handler\MigrationHandlerInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsGlossaryKeyMappingMigrationHandler implements MigrationHandlerInterface
{

    const SPY_CMS_GLOSSARY_KEY_MAPPINGS_PHP_NAME = 'SpyCmsGlossaryKeyMappings';
    const SPY_GLOSSARY_TRANSLATIONS_PHP_NAME = 'SpyGlossaryTranslations';
    const SPY_CMS_TEMPLATE_PHP_NAME = 'CmsTemplate';
    const SPY_GLOSSARY_KEY_PHP_NAME = 'GlossaryKey';

    /**
     * @var CmsGlossarySaverInterface
     */
    protected $cmsGlossarySaver;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param CmsGlossarySaverInterface $cmsGlossarySaver
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(CmsGlossarySaverInterface $cmsGlossarySaver, CmsQueryContainerInterface $queryContainer)
    {
        $this->cmsGlossarySaver = $cmsGlossarySaver;
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
            $targetData[static::SPY_CMS_GLOSSARY_KEY_MAPPINGS_PHP_NAME],
            $targetData[static::SPY_CMS_TEMPLATE_PHP_NAME][SpyCmsTemplateTableMap::COL_TEMPLATE_NAME]
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
     * @param string $templateName
     *
     * @return void
     */
    protected function createTargetGlossaryKeyMappings(array $glossaryKeyMappings, $templateName)
    {
        $glossaryAttributeTransfers = $this->createCmsGlossaryAttributeTransfers($glossaryKeyMappings, $templateName);
        $this->cmsGlossarySaver->saveCmsGlossary((new CmsGlossaryTransfer())->setGlossaryAttributes($glossaryAttributeTransfers));
    }

    /**
     * @param $glossaryKeyMappings
     * @param string $templateName
     *
     * @return \ArrayObject
     */
    protected function createCmsGlossaryAttributeTransfers($glossaryKeyMappings, $templateName)
    {
        $glossaryAttributeTransfers = new \ArrayObject();
        foreach ($glossaryKeyMappings as $glossaryKeyMapping) {
            $translations = $this->createCmsPlaceholderTranslationTransfers(
                $glossaryKeyMapping[static::SPY_GLOSSARY_KEY_PHP_NAME][static::SPY_GLOSSARY_TRANSLATIONS_PHP_NAME]
            );

            $glossaryAttributeTransfers[] = $this->createGlossaryAttributeTransfer(
                $translations,
                $glossaryKeyMapping[SpyCmsGlossaryKeyMappingTableMap::COL_FK_PAGE],
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
        foreach ($translations as $translation) {
            $newTranslation[] = $this->createCmsPlaceholderTranslationTransfer(
                $translation[SpyGlossaryTranslationTableMap::COL_VALUE],
                $translation[SpyGlossaryTranslationTableMap::COL_FK_LOCALE]
            );
        }

        return $newTranslation;
    }

    /**
     * @param string $value
     * @param int $idLocale
     *
     * @return CmsPlaceholderTranslationTransfer
     */
    protected function createCmsPlaceholderTranslationTransfer($value, $idLocale)
    {
        $cmsPlaceholderTranslation = new CmsPlaceholderTranslationTransfer();
        $cmsPlaceholderTranslation->setTranslation($value);
        $cmsPlaceholderTranslation->setFkLocale($idLocale);
        $cmsPlaceholderTranslation->setLocaleName($this->getLocalName($idLocale));

        return $cmsPlaceholderTranslation;
    }

    /**
     * @param int $idLocale
     *
     * @return string
     */
    protected function getLocalName($idLocale)
    {
        return $this->queryContainer->queryLocaleById($idLocale)->findOne()->getLocaleName();
    }

}
