<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsTemplateTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Spryker\Zed\Cms\Business\Mapping\CmsGlossarySaverInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class RevertManager implements RevertManagerInterface
{

    use DatabaseTransactionHandlerTrait;

    const SPY_CMS_GLOSSARY_KEY_MAPPINGS_PHP_NAME = 'SpyCmsGlossaryKeyMappings';
    const SPY_CMS_TEMPLATE_PHP_NAME = 'CmsTemplate';
    const SPY_GLOSSARY_KEY_PHP_NAME = 'GlossaryKey';
    const SPY_GLOSSARY_TRANSLATIONS_PHP_NAME = 'SpyGlossaryTranslations';
    const SPY_CMS_PAGE_LOCALIZED_ATTRIBUTES_PHP_NAME = 'SpyCmsPageLocalizedAttributess';

    /**
     * @var PublishManagerInterface
     */
    protected $publishManager;

    /**
     * @var VersionGeneratorInterface
     */
    protected $versionGenerator;

    /**
     * @var CmsGlossarySaverInterface
     */
    protected $cmsGlossarySaver;

    /**
     * @var CmsQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param PublishManagerInterface $publishManager
     * @param VersionGeneratorInterface $versionGenerator
     * @param CmsGlossarySaverInterface $cmsGlossarySaver
     * @param CmsQueryContainerInterface $queryContainer
     */
    public function __construct(
        PublishManagerInterface $publishManager,
        VersionGeneratorInterface $versionGenerator,
        CmsGlossarySaverInterface $cmsGlossarySaver,
        CmsQueryContainerInterface $queryContainer
    )
    {
        $this->publishManager = $publishManager;
        $this->versionGenerator = $versionGenerator;
        $this->cmsGlossarySaver = $cmsGlossarySaver;
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCmsVersionOrigin
     * @param int $idCmsVersionTarget
     *
     * @return bool
     */
    public function revertCmsVersion($idCmsVersionOrigin, $idCmsVersionTarget)
    {
        $originVersionEntity = $this->queryContainer->queryCmsVersionById($idCmsVersionOrigin)->findOne();
        $targetVersionEntity = $this->queryContainer->queryCmsVersionById($idCmsVersionTarget)->findOne();

        $this->migrateVersions($originVersionEntity, $targetVersionEntity);
    }

    /**
     * @param SpyCmsVersion $originVersionEntity
     * @param SpyCmsVersion $targetVersionEntity
     *
     * @return void
     */
    protected function migrateVersions(SpyCmsVersion $originVersionEntity, SpyCmsVersion $targetVersionEntity)
    {
        $this->handleDatabaseTransaction(function () use ($originVersionEntity, $targetVersionEntity) {
            $originData = json_decode($originVersionEntity->getData(), true);
            $targetData = json_decode($targetVersionEntity->getData(), true);

            $this->updateCmsTemplate($targetData);
            $this->updateCmsPageLocalizedAttributes($targetData);
            $this->updateCmsGlossaryKeyMapping($originData, $targetData);
            $this->publishAndVersion($originVersionEntity->getFkCmsPage(), $targetVersionEntity->getIdCmsVersion());
        });
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function updateCmsTemplate(array $data)
    {
        $templateData = $data[self::SPY_CMS_TEMPLATE_PHP_NAME];
        $cmsTemplateEntity = $this->queryContainer->queryTemplateByPath($templateData[SpyCmsTemplateTableMap::COL_TEMPLATE_PATH])->findOneOrCreate();

        if ($cmsTemplateEntity->isNew()) {
            $cmsTemplateEntity->setTemplateName($templateData[SpyCmsTemplateTableMap::COL_TEMPLATE_NAME]);
            $cmsTemplateEntity->save();
        }

        $cmsPageEntity = $this->queryContainer->queryPageById($data[SpyCmsPageTableMap::COL_ID_CMS_PAGE])->findOne();
        $cmsPageEntity->setCmsTemplate($cmsTemplateEntity);

        $cmsPageEntity->save();
    }

    /**
     * @param array $data
     *
     * @return void
     */
    protected function updateCmsPageLocalizedAttributes(array $data)
    {
        foreach ($data[self::SPY_CMS_PAGE_LOCALIZED_ATTRIBUTES_PHP_NAME] as $cmsPageLocalizedAttributes) {
            $cmsLocalizedAttribute = $this->queryContainer
                ->queryCmsPageLocalizedAttributesByFkPageAndFkLocale(
                    $cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_FK_CMS_PAGE],
                    $cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_FK_LOCALE]
                )
                ->findOne(); //TODO findOneOrCreate()

            $cmsLocalizedAttribute->setName($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_NAME]);
            $cmsLocalizedAttribute->setMetaTitle($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_TITLE]);
            $cmsLocalizedAttribute->setMetaKeywords($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_KEYWORDS]);
            $cmsLocalizedAttribute->setMetaDescription($cmsPageLocalizedAttributes[SpyCmsPageLocalizedAttributesTableMap::COL_META_DESCRIPTION]);

            $cmsLocalizedAttribute->save();
        }
    }

    /**
     * @param $originData
     * @param $targetData
     *
     * @return void
     */
    protected function updateCmsGlossaryKeyMapping($originData, $targetData)
    {
        $this->deleteOriginGlossaryKeyMappings($originData);
        $this->createTargetGlossaryKeyMappings($targetData);
    }

    /**
     * @param array $originData
     *
     * @return void
     */
    protected function deleteOriginGlossaryKeyMappings($originData)
    {
        $originGlossaryKeyIds = [];

        foreach ($originData[self::SPY_CMS_GLOSSARY_KEY_MAPPINGS_PHP_NAME] as $cmsGlossaryKeyMapping) {
            $originGlossaryKeyIds[] = (int)$cmsGlossaryKeyMapping[SpyCmsGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY];
        }

        $this->queryContainer->queryGlossaryKeyMappingsByFkGlossaryKeys($originGlossaryKeyIds)->delete();
        $this->queryContainer->queryGlossaryTranslationByFkGlossaryKeys($originGlossaryKeyIds)->delete();
        $this->queryContainer->queryGlossaryKeyByIdGlossaryKeys($originGlossaryKeyIds)->delete();
    }

    /**
     * @param $targetData
     *
     * @return void
     */
    protected function createTargetGlossaryKeyMappings($targetData)
    {
        $glossaryAttributeTransfers = $this->createCmsGlossaryAttributeTransfers($targetData);

        $this->cmsGlossarySaver->saveCmsGlossary((new CmsGlossaryTransfer())->setGlossaryAttributes($glossaryAttributeTransfers));
    }

    /**
     * @param $targetData
     *
     * @return \ArrayObject
     */
    protected function createCmsGlossaryAttributeTransfers($targetData)
    {
        $templateName = $targetData[self::SPY_CMS_TEMPLATE_PHP_NAME][SpyCmsTemplateTableMap::COL_TEMPLATE_NAME];
        $glossaryAttributeTransfers = new \ArrayObject();

        foreach ($targetData[self::SPY_CMS_GLOSSARY_KEY_MAPPINGS_PHP_NAME] as $targetCmsGlossaryKeyMapping) {
            $glossaryAttributeTransfer = new CmsGlossaryAttributesTransfer();
            $glossaryAttributeTransfer->setPlaceholder($targetCmsGlossaryKeyMapping[SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER]);
            $glossaryAttributeTransfer->setFkPage($targetCmsGlossaryKeyMapping[SpyCmsGlossaryKeyMappingTableMap::COL_FK_PAGE]);
            $glossaryAttributeTransfer->setTemplateName($templateName);
            $glossaryAttributeTransfer->setTranslations($this->getTranslation($targetCmsGlossaryKeyMapping));
            $glossaryAttributeTransfers[] = $glossaryAttributeTransfer;
        }

        return $glossaryAttributeTransfers;
    }

    /**
     * @param array $targetCmsGlossaryKeyMapping
     *
     * @return \ArrayObject
     */
    protected function getTranslation(array $targetCmsGlossaryKeyMapping)
    {
        $targetTranslationArray = $targetCmsGlossaryKeyMapping[self::SPY_GLOSSARY_KEY_PHP_NAME][self::SPY_GLOSSARY_TRANSLATIONS_PHP_NAME];

        return $this->createCmsPlaceholderTranslationTransfers($targetTranslationArray);
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
            $cmsPlaceholderTranslation = new CmsPlaceholderTranslationTransfer();
            $cmsPlaceholderTranslation->setTranslation($translation[SpyGlossaryTranslationTableMap::COL_VALUE]);
            $cmsPlaceholderTranslation->setFkLocale($translation[SpyGlossaryTranslationTableMap::COL_FK_LOCALE]);
            $cmsPlaceholderTranslation->setLocaleName($this->getLocalName((int)$translation[SpyGlossaryTranslationTableMap::COL_FK_LOCALE]));
            $newTranslation[] = $cmsPlaceholderTranslation;
        }

        return $newTranslation;
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

    /**
     * @param int $idCmsPage
     * @param int $idCmsVersion
     *
     * @return void
     */
    protected function publishAndVersion($idCmsPage, $idCmsVersion)
    {
        $this->publishManager->publishAndVersion($idCmsPage, $this->versionGenerator->generateReferenceCmsVersionName($idCmsVersion));
    }
}
