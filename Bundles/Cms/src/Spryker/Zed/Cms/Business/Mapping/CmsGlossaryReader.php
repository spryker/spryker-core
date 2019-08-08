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
use Spryker\Zed\Cms\Business\Exception\MissingPlaceholdersException;
use Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsGlossaryReader implements CmsGlossaryReaderInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\Cms\CmsConfig
     */
    protected $cmsConfig;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToLocaleFacadeInterface $localeFacade,
        CmsConfig $cmsConfig
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->cmsConfig = $cmsConfig;
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
     * @return string
     */
    protected function getPlaceholderPattern(): string
    {
        return $this->cmsConfig->getPlaceholderPattern();
    }

    /**
     * @return string
     */
    protected function getPlaceholderValuePattern(): string
    {
        return $this->cmsConfig->getPlaceholderValuePattern();
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return array
     */
    protected function findPagePlaceholders(SpyCmsPage $cmsPageEntity): array
    {
        $cmsPageArray = $cmsPageEntity->toArray();
        $templateFiles = $this->cmsConfig->getTemplateRealPaths($cmsPageArray[CmsQueryContainer::TEMPLATE_PATH]);

        $placeholders = [];
        foreach ($templateFiles as $templateFile) {
            if (!$this->fileExists($templateFile)) {
                continue;
            }

            $placeholders = $this->getTemplatePlaceholders($templateFile);
        }

        return $placeholders;
    }

    /**
     * @param string $templateFile
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPlaceholdersException
     * @throws \Spryker\Zed\Cms\Business\Exception\TemplateFileNotFoundException
     *
     * @return array
     */
    protected function getTemplatePlaceholders(string $templateFile): array
    {
        if (!$this->fileExists($templateFile)) {
            throw new TemplateFileNotFoundException(
                sprintf('Template file not found in "%s"', $templateFile)
            );
        }

        $templateContent = $this->readTemplateContents($templateFile);

        preg_match_all($this->getPlaceholderPattern(), $templateContent, $cmsPlaceholderLine);
        if (count($cmsPlaceholderLine) === 0) {
            throw new MissingPlaceholdersException(
                sprintf(
                    'No placeholders found in "%s" template.',
                    $templateFile
                )
            );
        }

        preg_match_all($this->getPlaceholderValuePattern(), implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

        return $placeholderMap[1];
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

    /**
     * @param string $templateFile
     *
     * @return string
     */
    protected function readTemplateContents(string $templateFile): string
    {
        $templateFileContent = file_get_contents($templateFile);

        return $templateFileContent;
    }

    /**
     * @param string $templateFile
     *
     * @return bool
     */
    protected function fileExists(string $templateFile): bool
    {
        return file_exists($templateFile);
    }
}
