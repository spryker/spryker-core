<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Business\Exception\MissingPlaceholdersException;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsGlossaryReader implements CmsGlossaryReaderInterface
{

    const CMS_PLACEHOLDER_PATTERN = '/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9_-]*" -->/';
    const CMS_PLACEHOLDER_VALUE_PATTERN = '/"([^"]+)"/';

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $cmsConfig;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToLocaleInterface $localeFacade,
        CmsConfig $cmsConfig
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->cmsConfig = $cmsConfig;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function getPageGlossaryAttributes($idCmsPage)
    {
        $cmsPageEntity = $this->getCmsPageEntity($idCmsPage);

        $pagePlaceholders = $this->findPagePlaceholders($cmsPageEntity);
        $glossaryKeyEntityMap = $this->createKeyMappingByPlaceholder($pagePlaceholders, $idCmsPage);

        $cmsGlossaryTransfer = new CmsGlossaryTransfer();
        foreach ($pagePlaceholders as $pagePlaceholder) {
            $glossaryAttributeTransfer = $this->mapGlossaryAttributeTransfer($cmsPageEntity, $pagePlaceholder);
            $this->addGlossaryAttributeTranslations($glossaryKeyEntityMap, $pagePlaceholder, $glossaryAttributeTransfer);
            $cmsGlossaryTransfer->addGlossaryAttribute($glossaryAttributeTransfer);
        }

        return $cmsGlossaryTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return array
     */
    protected function findPagePlaceholders(SpyCmsPage $cmsPageEntity)
    {
        $cmsPageArray = $cmsPageEntity->toArray();
        $templateFile = $this->cmsConfig->getTemplateRealPath($cmsPageArray[CmsQueryContainer::TEMPLATE_PATH]);

        $placeholders = $this->getTemplatePlaceholders($templateFile);

        return $placeholders;
    }

    /**
     * @param string $templateFile
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPlaceholdersException
     *
     * @return array
     */
    protected function getTemplatePlaceholders($templateFile)
    {
        if (!file_exists($templateFile)) {
            return [];
        }

        $templateContent = file_get_contents($templateFile);

        preg_match_all(static::CMS_PLACEHOLDER_PATTERN, $templateContent, $cmsPlaceholderLine);
        if (count($cmsPlaceholderLine) == 0) {
            throw new MissingPlaceholdersException(
                sprintf(
                    'No placeholders found in "%s" template.',
                    $templateFile
                )
            );
        }

        preg_match_all(static::CMS_PLACEHOLDER_VALUE_PATTERN, implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

        return $placeholderMap[1];
    }

    /**
     * @param array $placeholders,
     * @param int $idCmsPage
     *
     * @return array|\Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[]
     */
    protected function createKeyMappingByPlaceholder(array $placeholders, $idCmsPage)
    {
        $glossaryKeyMappingCollection = $this->getGlossaryMappingCollection($placeholders, $idCmsPage);

        $placeholderMap = [];
        foreach ($glossaryKeyMappingCollection as $glossaryKeyMappingEntity) {
            $placeholderMap[$glossaryKeyMappingEntity->getPlaceholder()] = $glossaryKeyMappingEntity;
        }
        return $placeholderMap;
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryKey $glossaryKeyEntity
     * @param int $idLocale
     *
     * @return null|string
     */
    protected function findTranslation(SpyGlossaryKey $glossaryKeyEntity, $idLocale)
    {
        foreach ($glossaryKeyEntity->getSpyGlossaryTranslations() as $glossaryTranslationEntity) {
            if ($glossaryTranslationEntity->getFkLocale() !== $idLocale) {
                continue;
            }
            return $glossaryTranslationEntity->getValue();

        }
        return null;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     * @param string $pagePlaceholder
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer
     */
    protected function mapGlossaryAttributeTransfer(SpyCmsPage $cmsPageEntity, $pagePlaceholder)
    {
        $glossaryAttributeTransfer = new CmsGlossaryAttributesTransfer();
        $glossaryAttributeTransfer->fromArray($cmsPageEntity->toArray(), true);
        $glossaryAttributeTransfer->setFkPage($cmsPageEntity->getIdCmsPage());
        $glossaryAttributeTransfer->setPlaceholder($pagePlaceholder);

        return $glossaryAttributeTransfer;
    }

    /**
     * @param array|\Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[] $glossaryKeyEntityMap
     * @param string $pagePlaceholder
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributeTransfer
     *
     * @return void
     */
    protected function addGlossaryAttributeTranslations(
        array $glossaryKeyEntityMap,
        $pagePlaceholder,
        CmsGlossaryAttributesTransfer $glossaryAttributeTransfer
    ) {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        foreach ($availableLocales as $idLocale => $localeName) {

            $cmsPlaceholderTranslationTransfer = new CmsPlaceholderTranslationTransfer();
            $cmsPlaceholderTranslationTransfer->setFkLocale($idLocale);
            $cmsPlaceholderTranslationTransfer->setLocaleName($localeName);

            if (!isset($glossaryKeyEntityMap[$pagePlaceholder])) {
                $glossaryAttributeTransfer->addTranslation($cmsPlaceholderTranslationTransfer);
                continue;
            }

            $glossaryKeyMappingEntity = $glossaryKeyEntityMap[$pagePlaceholder];
            $glossaryAttributeTransfer->setFkCmsGlossaryMapping($glossaryKeyMappingEntity->getIdCmsGlossaryKeyMapping());

            $this->setTranslationValue($glossaryKeyMappingEntity, $cmsPlaceholderTranslationTransfer);

            $glossaryKeyEntity = $glossaryKeyMappingEntity->getGlossaryKey();
            $glossaryAttributeTransfer->setFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey());
            $glossaryAttributeTransfer->setTranslationKey($glossaryKeyEntity->getKey());

            $glossaryAttributeTransfer->addTranslation($cmsPlaceholderTranslationTransfer);

        }
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function getCmsPageEntity($idCmsPage)
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageWithTemplatesAndUrlByIdPage($idCmsPage)
            ->findOne();

        if ($cmsPageEntity === null) {
            throw new MissingPageException(
                sprintf(
                    'CMS page with id "%d" not found!',
                    $idCmsPage
                )
            );
        }
        return $cmsPageEntity;
    }

    /**
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getGlossaryMappingCollection(array $placeholders, $idCmsPage)
    {
        $glossaryKeyMappingCollection = $this->cmsQueryContainer
            ->queryGlossaryKeyMappingByPlaceholdersAndIdPage($placeholders, $idCmsPage)
            ->find();

        return $glossaryKeyMappingCollection;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping $glossaryKeyMappingEntity
     * @param \Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer $cmsPlaceholderTranslationTransfer
     *
     * @return void
     */
    protected function setTranslationValue(
        SpyCmsGlossaryKeyMapping $glossaryKeyMappingEntity,
        CmsPlaceholderTranslationTransfer $cmsPlaceholderTranslationTransfer
    ) {
        $cmsPlaceholderTranslationTransfer->requireFkLocale();

        $glossaryKeyEntity = $glossaryKeyMappingEntity->getGlossaryKey();
        $translationValue = $this->findTranslation($glossaryKeyEntity, $cmsPlaceholderTranslationTransfer->getFkLocale());
        $cmsPlaceholderTranslationTransfer->setTranslation($translationValue);
    }

}
