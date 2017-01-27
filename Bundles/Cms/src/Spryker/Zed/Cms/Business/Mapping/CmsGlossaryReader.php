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
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToLocaleInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\Propel\Business\Runtime\ActiveQuery\Criteria;

class CmsGlossaryReader implements CmsGlossaryReaderInterface
{

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
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     *
     */
    public function getPageGlossaryAttributes($idCmsPage)
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
        $pageUrlArray = $cmsPageEntity->toArray();
        $tempFile = $this->cmsConfig->getTemplateRealPath($pageUrlArray[CmsQueryContainer::TEMPLATE_PATH]);

        $placeholders = $this->findTemplatePlaceholders($tempFile);

        return $placeholders;
    }

    /**
     * @param string $tempFile
     *
     * @return array
     */
    protected function findTemplatePlaceholders($tempFile)
    {
        $placeholderMap = [];

        if (file_exists($tempFile)) {
            $fileContent = file_get_contents($tempFile);

            preg_match_all('/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9_-]*" -->/', $fileContent, $cmsPlaceholderLine);
            preg_match_all('/"([^"]+)"/', implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

            return $placeholderMap[1];
        }

        return $placeholderMap;
    }

    /**
     * @param array $placeholders,
     * @param int $idCmsPage
     *
     * @return array|SpyCmsGlossaryKeyMapping[]
     */
    protected function createKeyMappingByPlaceholder(array $placeholders, $idCmsPage)
    {
        $glossaryKeyMappingCollection = $this->cmsQueryContainer
            ->queryGlossaryKeyMappings()
            ->leftJoinGlossaryKey()
            ->filterByPlaceholder($placeholders, Criteria::IN)
            ->filterByFkPage($idCmsPage)
            ->find();

        $placeholderMap = [];
        foreach ($glossaryKeyMappingCollection as $glossaryKeyMappingEntity) {
            $placeholderMap[$glossaryKeyMappingEntity->getPlaceholder()] = $glossaryKeyMappingEntity;
        }
        return $placeholderMap;
    }

    /**
     * @param SpyGlossaryKey $glossaryKeyEntity
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
     * @param SpyCmsPage $cmsPageEntity
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
     * @param array|SpyCmsGlossaryKeyMapping[] $glossaryKeyEntityMap
     * @param string $pagePlaceholder
     * @param CmsGlossaryAttributesTransfer $glossaryAttributeTransfer
     */
    protected function addGlossaryAttributeTranslations(
        array $glossaryKeyEntityMap,
        $pagePlaceholder,
        CmsGlossaryAttributesTransfer $glossaryAttributeTransfer
    ) {
        $availableLocales = $this->localeFacade->getAvailableLocales();

        foreach ($availableLocales as $idLocale => $localeName)
        {
            $cmsPlaceholderTranslationTransfer = new CmsPlaceholderTranslationTransfer();
            $cmsPlaceholderTranslationTransfer->setFkLocale($idLocale);
            $cmsPlaceholderTranslationTransfer->setLocaleName($localeName);

            if (isset($glossaryKeyEntityMap[$pagePlaceholder])) {

                $glossaryKeyMappingEntity = $glossaryKeyEntityMap[$pagePlaceholder];
                $glossaryAttributeTransfer->setFkCmsGlossaryMapping($glossaryKeyMappingEntity->getIdCmsGlossaryKeyMapping());

                $glossaryKeyEntity = $glossaryKeyMappingEntity->getGlossaryKey();
                if ($glossaryKeyEntity !== null) {
                    $translationValue = $this->findTranslation($glossaryKeyEntity, $idLocale);
                    $cmsPlaceholderTranslationTransfer->setTranslation($translationValue);
                }

                $glossaryAttributeTransfer->setFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey());
                $glossaryAttributeTransfer->setTranslationKey($glossaryKeyEntity->getKey());
            }
            $glossaryAttributeTransfer->addTranslation($cmsPlaceholderTranslationTransfer);
        }
    }
}

