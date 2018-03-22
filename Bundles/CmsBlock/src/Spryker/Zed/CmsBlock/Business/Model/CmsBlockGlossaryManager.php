<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException;
use Spryker\Zed\CmsBlock\CmsBlockConfig;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToLocaleInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;

class CmsBlockGlossaryManager implements CmsBlockGlossaryManagerInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlock\CmsBlockConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlock\CmsBlockConfig $cmsBlockConfig
     * @param \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToLocaleInterface $cmsBlockToLocaleFacade
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockConfig $cmsBlockConfig,
        CmsBlockToLocaleInterface $cmsBlockToLocaleFacade
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->config = $cmsBlockConfig;
        $this->localeFacade = $cmsBlockToLocaleFacade;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function findPlaceholders($idCmsBlock)
    {
        $spyCmsBlock = $this->getCmsBlockEntity($idCmsBlock);

        if ($spyCmsBlock === null) {
            return $this->createGlossaryTransfer();
        }

        $placeholders = $this->findCmsBlockPlaceholders($spyCmsBlock->getCmsBlockTemplate());
        $glossaryKeyEntityMap = $this->createKeyMappingByPlaceholder($idCmsBlock, $placeholders);
        $glossaryTransfer = $this->mapGlossaryTransfer($spyCmsBlock);

        foreach ($placeholders as $placeholder) {
            $glossaryPlaceholderTransfer = $this->mapGlossaryPlaceholderTransfer($spyCmsBlock, $placeholder);
            $this->addGlossaryAttributeTranslations($glossaryKeyEntityMap, $placeholder, $glossaryPlaceholderTransfer);
            $glossaryTransfer->addGlossaryPlaceholder($glossaryPlaceholderTransfer);
        }

        return $glossaryTransfer;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate $spyCmsBlockTemplate
     *
     * @return array
     */
    protected function findCmsBlockPlaceholders(SpyCmsBlockTemplate $spyCmsBlockTemplate)
    {
        $templateFiles = $this->config->getTemplateRealPaths($spyCmsBlockTemplate->getTemplatePath());

        foreach ($templateFiles as $templateFile) {
            if (is_readable($templateFile)) {
                return $this->getTemplatePlaceholders($templateFile);
            }
        }

        return [];
    }

    /**
     * @param string $templateFile
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException
     *
     * @return array
     */
    protected function getTemplatePlaceholders($templateFile)
    {
        if (!is_readable($templateFile)) {
            throw new CmsBlockTemplateNotFoundException(
                sprintf('Template file not found in "%s"', $templateFile)
            );
        }

        $templateContent = $this->readTemplateContents($templateFile);

        preg_match_all($this->config->getPlaceholderPattern(), $templateContent, $cmsPlaceholderLine);
        if (count($cmsPlaceholderLine) == 0) {
            return [];
        }

        preg_match_all($this->config->getPlaceholderValuePattern(), implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

        return $placeholderMap[1];
    }

    /**
     * @param string $templateFile
     *
     * @return string
     */
    protected function readTemplateContents($templateFile)
    {
        return file_get_contents($templateFile);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock
     */
    protected function getCmsBlockEntity($idCmsBlock)
    {
        return $this->cmsBlockQueryContainer
            ->queryCmsBlockByIdWithTemplateWithGlossary($idCmsBlock)
            ->find()
            ->getFirst();
    }

    /**
     * @param int $idCmsBlock
     * @param array $placeholders
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping[]
     */
    protected function createKeyMappingByPlaceholder($idCmsBlock, array $placeholders)
    {
        $glossaryKeyMappingCollection = $this->getGlossaryMappingCollection($idCmsBlock, $placeholders);

        $placeholderMap = [];
        foreach ($glossaryKeyMappingCollection as $glossaryKeyMappingEntity) {
            $placeholderMap[$glossaryKeyMappingEntity->getPlaceholder()] = $glossaryKeyMappingEntity;
        }

        return $placeholderMap;
    }

    /**
     * @param int $idCmsBlock
     * @param array $placeholders
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getGlossaryMappingCollection($idCmsBlock, array $placeholders)
    {
        $glossaryKeyMappingCollection = $this->cmsBlockQueryContainer
            ->queryGlossaryKeyMappingByPlaceholdersAndIdCmsBlock($placeholders, $idCmsBlock)
            ->find();

        return $glossaryKeyMappingCollection;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function mapGlossaryTransfer(SpyCmsBlock $spyCmsBlock)
    {
        $glossaryTransfer = $this->createGlossaryTransfer();
        $glossaryTransfer->fromArray($spyCmsBlock->toArray(), true);

        return $glossaryTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function createGlossaryTransfer()
    {
        return new CmsBlockGlossaryTransfer();
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     * @param string $placeholder
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer
     */
    protected function mapGlossaryPlaceholderTransfer(SpyCmsBlock $spyCmsBlock, $placeholder)
    {
        $glossaryPlaceholderTransfer = new CmsBlockGlossaryPlaceholderTransfer();
        $glossaryPlaceholderTransfer->setFkCmsBlock($spyCmsBlock->getIdCmsBlock());
        $glossaryPlaceholderTransfer->setPlaceholder($placeholder);
        $glossaryPlaceholderTransfer->setTemplateName($spyCmsBlock->getCmsBlockTemplate()->getTemplateName());

        return $glossaryPlaceholderTransfer;
    }

    /**
     * @param array $glossaryKeyEntityMap
     * @param string $placeholder
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return void
     */
    protected function addGlossaryAttributeTranslations(
        array $glossaryKeyEntityMap,
        $placeholder,
        CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
    ) {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        foreach ($availableLocales as $idLocale => $localeName) {
            $translationTransfer = new CmsBlockGlossaryPlaceholderTranslationTransfer();
            $translationTransfer->setFkLocale($idLocale);
            $translationTransfer->setLocaleName($localeName);

            if (isset($glossaryKeyEntityMap[$placeholder])) {
                /** @var \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping $spyCmsBlockGlossaryKeyMapping */
                $spyCmsBlockGlossaryKeyMapping = $glossaryKeyEntityMap[$placeholder];
                $glossaryPlaceholderTransfer->setIdCmsBlockGlossaryKeyMapping($spyCmsBlockGlossaryKeyMapping->getIdCmsBlockGlossaryKeyMapping());

                $this->setTranslationValue($spyCmsBlockGlossaryKeyMapping, $translationTransfer);

                $glossaryKeyEntity = $spyCmsBlockGlossaryKeyMapping->getGlossaryKey();
                $glossaryPlaceholderTransfer->setFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey());
                $glossaryPlaceholderTransfer->setTranslationKey($glossaryKeyEntity->getKey());
            }

            $glossaryPlaceholderTransfer->addTranslation($translationTransfer);
        }
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping $glossaryKeyMappingEntity
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer $cmsPlaceholderTranslationTransfer
     *
     * @return void
     */
    protected function setTranslationValue(
        SpyCmsBlockGlossaryKeyMapping $glossaryKeyMappingEntity,
        CmsBlockGlossaryPlaceholderTranslationTransfer $cmsPlaceholderTranslationTransfer
    ) {
        $cmsPlaceholderTranslationTransfer->requireFkLocale();

        $glossaryKeyEntity = $glossaryKeyMappingEntity->getGlossaryKey();
        $translationValue = $this->findTranslation($glossaryKeyEntity, $cmsPlaceholderTranslationTransfer->getFkLocale());
        $cmsPlaceholderTranslationTransfer->setTranslation($translationValue);
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryKey $spyGlossaryKey
     * @param int $idLocale
     *
     * @return null|string
     */
    protected function findTranslation(SpyGlossaryKey $spyGlossaryKey, $idLocale)
    {
        foreach ($spyGlossaryKey->getSpyGlossaryTranslations() as $glossaryTranslationEntity) {
            if ($glossaryTranslationEntity->getFkLocale() === $idLocale) {
                return $glossaryTranslationEntity->getValue();
            }
        }

        return null;
    }
}
