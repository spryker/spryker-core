<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsGlossaryKeyMappingTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;
use Spryker\Zed\Cms\Business\Exception\MappingAmbiguousException;
use Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException;
use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Throwable;

class CmsGlossarySaver implements CmsGlossarySaverInterface
{
    public const DEFAULT_TRANSLATION = '';

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\Cms\Business\Mapping\CmsGlossaryKeyGeneratorInterface
     */
    protected $cmsGlossaryKeyGenerator;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\Cms\Business\Mapping\CmsGlossaryKeyGeneratorInterface $cmsGlossaryKeyGenerator
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToGlossaryFacadeInterface $glossaryFacade,
        CmsGlossaryKeyGeneratorInterface $cmsGlossaryKeyGenerator
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->glossaryFacade = $glossaryFacade;
        $this->cmsGlossaryKeyGenerator = $cmsGlossaryKeyGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @throws \Throwable
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function saveCmsGlossary(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        try {
            $this->cmsQueryContainer->getConnection()->beginTransaction();

            foreach ($cmsGlossaryTransfer->getGlossaryAttributes() as $glossaryAttributesTransfer) {
                $translationKey = $this->resolveTranslationKey($glossaryAttributesTransfer);
                $glossaryAttributesTransfer->setTranslationKey($translationKey);

                $this->translatePlaceholder($glossaryAttributesTransfer, $translationKey);

                $idCmsGlossaryMapping = $this->saveCmsGlossaryKeyMapping($glossaryAttributesTransfer);
                $glossaryAttributesTransfer->setFkCmsGlossaryMapping($idCmsGlossaryMapping);
            }
            $this->cmsQueryContainer->getConnection()->commit();
        } catch (Throwable $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        return $cmsGlossaryTransfer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deleteCmsGlossary(int $idCmsPage): void
    {
        $idGlossaryKeys = $this->cmsQueryContainer->queryGlossaryKeyMappingsByPageId($idCmsPage)
            ->select(SpyCmsGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY)
            ->find()
            ->toArray();

        if (empty($idGlossaryKeys)) {
            return;
        }

        $this->cmsQueryContainer->queryGlossaryKeyMappingsByFkGlossaryKeys($idGlossaryKeys)->delete();
        $this->glossaryFacade->deleteTranslationsByFkKeys($idGlossaryKeys);
        $this->glossaryFacade->deleteKeys($idGlossaryKeys);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributesTransfer
     *
     * @return int
     */
    protected function saveCmsGlossaryKeyMapping(CmsGlossaryAttributesTransfer $glossaryAttributesTransfer): int
    {
        if ($glossaryAttributesTransfer->getFkCmsGlossaryMapping() === null) {
            return $this->createPageKeyMapping($glossaryAttributesTransfer);
        } else {
            return $this->updatePageKeyMapping($glossaryAttributesTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer
     *
     * @return int
     */
    protected function createPageKeyMapping(CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer): int
    {
        $this->checkPagePlaceholderNotAmbiguous(
            $cmsGlossaryAttributesTransfer->getFkPage(),
            $cmsGlossaryAttributesTransfer->getPlaceholder()
        );

        $cmsGlossaryKeyMappingEntity = $this->createCmsGlossaryKeyMappingEntity();
        $cmsGlossaryKeyMappingEntity->fromArray($cmsGlossaryAttributesTransfer->toArray());

        $cmsGlossaryKeyMappingEntity->save();

        return $cmsGlossaryKeyMappingEntity->getPrimaryKey();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer
     *
     * @return int
     */
    protected function updatePageKeyMapping(CmsGlossaryAttributesTransfer $cmsGlossaryAttributesTransfer): int
    {
        $glossaryKeyMappingEntity = $this->getGlossaryKeyMappingById($cmsGlossaryAttributesTransfer->getFkCmsGlossaryMapping());
        $glossaryKeyMappingEntity->fromArray($cmsGlossaryAttributesTransfer->modifiedToArray());

        if (!$glossaryKeyMappingEntity->isModified()) {
            return $glossaryKeyMappingEntity->getPrimaryKey();
        }

        $isPlaceholderModified = $glossaryKeyMappingEntity->isColumnModified(SpyCmsGlossaryKeyMappingTableMap::COL_PLACEHOLDER);
        $isPageIdModified = $glossaryKeyMappingEntity->isColumnModified(SpyCmsGlossaryKeyMappingTableMap::COL_FK_PAGE);

        if ($isPlaceholderModified || $isPageIdModified) {
            $this->checkPagePlaceholderNotAmbiguous(
                $cmsGlossaryAttributesTransfer->getFkPage(),
                $cmsGlossaryAttributesTransfer->getPlaceholder()
            );
        }

        $glossaryKeyMappingEntity->save();

        return $glossaryKeyMappingEntity->getPrimaryKey();
    }

    /**
     * @param int $idMapping
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingGlossaryKeyMappingException
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping
     */
    protected function getGlossaryKeyMappingById(int $idMapping): SpyCmsGlossaryKeyMapping
    {
        $mappingEntity = $this->findGlossaryKeyMappingEntityById($idMapping);

        if (!$mappingEntity) {
            throw new MissingGlossaryKeyMappingException(sprintf('Tried to retrieve a missing glossary key mapping with id %s', $idMapping));
        }

        return $mappingEntity;
    }

    /**
     * @param int|null $idPage
     * @param string $placeholder
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MappingAmbiguousException
     *
     * @return void
     */
    protected function checkPagePlaceholderNotAmbiguous(?int $idPage, string $placeholder): void
    {
        if ($this->hasPagePlaceholderMapping($idPage, $placeholder)) {
            throw new MappingAmbiguousException(sprintf('Tried to create an ambiguous mapping for placeholder %s on page %s', $placeholder, $idPage));
        }
    }

    /**
     * @param int|null $idPage
     * @param string $placeholder
     *
     * @return bool
     */
    protected function hasPagePlaceholderMapping(?int $idPage, string $placeholder): bool
    {
        $mappingCount = $this->cmsQueryContainer
            ->queryGlossaryKeyMapping($idPage, $placeholder)
            ->count();

        return $mappingCount > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributesTransfer
     *
     * @return string
     */
    protected function resolveTranslationKey(CmsGlossaryAttributesTransfer $glossaryAttributesTransfer): string
    {
        $translationKey = $glossaryAttributesTransfer->getTranslationKey();
        if (!$glossaryAttributesTransfer->getTranslationKey()) {
            $translationKey = $this->cmsGlossaryKeyGenerator->generateGlossaryKeyName(
                $glossaryAttributesTransfer->getFkPage(),
                $glossaryAttributesTransfer->getTemplateName(),
                $glossaryAttributesTransfer->getPlaceholder()
            );
        }

        return $translationKey;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryAttributesTransfer $glossaryAttributesTransfer
     * @param string $translationKey
     *
     * @return void
     */
    protected function translatePlaceholder(CmsGlossaryAttributesTransfer $glossaryAttributesTransfer, string $translationKey): void
    {
        foreach ($glossaryAttributesTransfer->getTranslations() as $glossaryTranslationTransfer) {
            $this->setDefaultTranslation($glossaryTranslationTransfer);
            $keyTranslationTransfer = $this->createTranslationTransfer($translationKey, $glossaryTranslationTransfer);
            $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
        }

        $glossaryKeyEntity = $this->findGlossaryKeyEntityByTranslationKey($translationKey);
        if ($glossaryKeyEntity === null) {
            return;
        }

        $glossaryAttributesTransfer->setFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey());
    }

    /**
     * @param string $translationKey
     * @param \Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer $glossaryTranslationTransfer
     *
     * @return \Generated\Shared\Transfer\KeyTranslationTransfer
     */
    protected function createTranslationTransfer(string $translationKey, CmsPlaceholderTranslationTransfer $glossaryTranslationTransfer): KeyTranslationTransfer
    {
        $keyTranslationTransfer = new KeyTranslationTransfer();
        $keyTranslationTransfer->setGlossaryKey($translationKey);

        $keyTranslationTransfer->setLocales([
            $glossaryTranslationTransfer->getLocaleName() => $glossaryTranslationTransfer->getTranslation(),
        ]);

        return $keyTranslationTransfer;
    }

    /**
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping
     */
    protected function createCmsGlossaryKeyMappingEntity(): SpyCmsGlossaryKeyMapping
    {
        return new SpyCmsGlossaryKeyMapping();
    }

    /**
     * @param string $translationKey
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKey|null
     */
    protected function findGlossaryKeyEntityByTranslationKey(string $translationKey): ?SpyGlossaryKey
    {
        return $this->cmsQueryContainer
            ->queryKey($translationKey)
            ->findOne();
    }

    /**
     * @param int $idMapping
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping|null
     */
    protected function findGlossaryKeyMappingEntityById(int $idMapping): ?SpyCmsGlossaryKeyMapping
    {
        return $this->cmsQueryContainer
            ->queryGlossaryKeyMappingById($idMapping)
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPlaceholderTranslationTransfer $glossaryTranslationTransfer
     *
     * @return void
     */
    protected function setDefaultTranslation(CmsPlaceholderTranslationTransfer $glossaryTranslationTransfer): void
    {
        if ($glossaryTranslationTransfer->getTranslation() === null) {
            $glossaryTranslationTransfer->setTranslation(static::DEFAULT_TRANSLATION);
        }
    }
}
