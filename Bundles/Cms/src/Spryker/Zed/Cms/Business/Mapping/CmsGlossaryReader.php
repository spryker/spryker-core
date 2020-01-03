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
use Spryker\Zed\Cms\Business\Template\TemplateReaderInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsGlossaryReader implements CmsGlossaryReaderInterface
{
    /**
     * @var string
     * @uses \Spryker\Zed\Cms\Persistence\CmsQueryContainer::TEMPLATE_PATH
     */
    protected const COLUMN_TEMPLATE_PATH = 'template_path';

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Cms\Business\Template\TemplateReaderInterface
     */
    protected $templateReader;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\Cms\Business\Template\TemplateReaderInterface $templateReader
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToLocaleFacadeInterface $localeFacade,
        TemplateReaderInterface $templateReader
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->templateReader = $templateReader;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer|null
     */
    public function findPageGlossaryAttributes(int $idCmsPage): ?CmsGlossaryTransfer
    {
        $cmsPageEntity = $this->getCmsPageEntity($idCmsPage);

        if ($cmsPageEntity === null) {
            return null;
        }

        $pageTemplatePlaceholders = $this->templateReader->getPlaceholdersByTemplatePath(
            $cmsPageEntity->getVirtualColumn(static::COLUMN_TEMPLATE_PATH)
        );
        $glossaryKeyEntityMap = $this->createKeyMappingByPlaceholder($pageTemplatePlaceholders, $idCmsPage);

        $cmsGlossaryTransfer = new CmsGlossaryTransfer();
        foreach ($pageTemplatePlaceholders as $pagePlaceholder) {
            $glossaryAttributeTransfer = $this->mapGlossaryAttributeTransfer($cmsPageEntity, $pagePlaceholder);
            $this->addGlossaryAttributeTranslations($glossaryKeyEntityMap, $pagePlaceholder, $glossaryAttributeTransfer);
            $cmsGlossaryTransfer->addGlossaryAttribute($glossaryAttributeTransfer);
        }

        return $cmsGlossaryTransfer;
    }

    /**
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[]
     */
    protected function createKeyMappingByPlaceholder(array $placeholders, int $idCmsPage): array
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
     * @return string|null
     */
    protected function findTranslation(SpyGlossaryKey $glossaryKeyEntity, int $idLocale): ?string
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
    protected function mapGlossaryAttributeTransfer(SpyCmsPage $cmsPageEntity, string $pagePlaceholder): CmsGlossaryAttributesTransfer
    {
        $glossaryAttributeTransfer = new CmsGlossaryAttributesTransfer();
        $glossaryAttributeTransfer->fromArray($cmsPageEntity->toArray(), true);
        $glossaryAttributeTransfer->setFkPage($cmsPageEntity->getIdCmsPage());
        $glossaryAttributeTransfer->setPlaceholder($pagePlaceholder);

        return $glossaryAttributeTransfer;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[] $glossaryKeyEntityMap
     * @param string $pagePlaceholder
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributeTransfer
     *
     * @return void
     */
    protected function addGlossaryAttributeTranslations(
        array $glossaryKeyEntityMap,
        string $pagePlaceholder,
        CmsGlossaryAttributesTransfer $glossaryAttributeTransfer
    ): void {
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
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|null
     */
    protected function getCmsPageEntity(int $idCmsPage): ?SpyCmsPage
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageWithTemplatesAndUrlByIdPage($idCmsPage)
            ->findOne();

        return $cmsPageEntity;
    }

    /**
     * @param array $placeholders
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getGlossaryMappingCollection(array $placeholders, int $idCmsPage)
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
    ): void {
        $cmsPlaceholderTranslationTransfer->requireFkLocale();

        $glossaryKeyEntity = $glossaryKeyMappingEntity->getGlossaryKey();
        $translationValue = $this->findTranslation($glossaryKeyEntity, $cmsPlaceholderTranslationTransfer->getFkLocale());
        $cmsPlaceholderTranslationTransfer->setTranslation($translationValue);
    }
}
