<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;
use Spryker\Shared\CmsBlock\CmsBlockConfig;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockMappingAmbiguousException;
use Spryker\Zed\CmsBlock\Business\Exception\MissingCmsBlockGlossaryKeyMapping;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryInterface;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchInterface;
use Spryker\Zed\CmsBlock\Dependency\QueryContainer\CmsBlockToGlossaryQueryContainerInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockGlossaryWriter implements CmsBlockGlossaryWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    const DEFAULT_TRANSLATION = '';

    /**
     * @var \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGeneratorInterface
     */
    protected $cmsBlockGlossaryKeyGenerator;

    /**
     * @var \Spryker\Zed\CmsBlock\Dependency\QueryContainer\CmsBlockToGlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryInterface $glossaryFacade
     * @param \Spryker\Zed\CmsBlock\Business\Model\CmsBlockGlossaryKeyGeneratorInterface $cmsBlockGlossaryKeyGenerator
     * @param \Spryker\Zed\CmsBlock\Dependency\QueryContainer\CmsBlockToGlossaryQueryContainerInterface $glossaryQueryContainer
     * @param \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchInterface $touchFacade
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockToGlossaryInterface $glossaryFacade,
        CmsBlockGlossaryKeyGeneratorInterface $cmsBlockGlossaryKeyGenerator,
        CmsBlockToGlossaryQueryContainerInterface $glossaryQueryContainer,
        CmsBlockToTouchInterface $touchFacade
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->glossaryFacade = $glossaryFacade;
        $this->cmsBlockGlossaryKeyGenerator = $cmsBlockGlossaryKeyGenerator;
        $this->glossaryQueryContainer = $glossaryQueryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deleteByCmsBlockId($idCmsBlock)
    {
        $glossaryKeys = $this->cmsBlockQueryContainer
            ->queryCmsBlockGlossaryKeyMappingByIdCmsBlock($idCmsBlock)
            ->find()
            ->getColumnValues('FkGlossaryKey');

        if (empty($glossaryKeys)) {
            return;
        }

        $this->handleDatabaseTransaction(function () use ($glossaryKeys) {
            $this->deleteGlossaryKeysTransaction($glossaryKeys);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($cmsBlockGlossaryTransfer) {
            $this->saveCmsGlossaryTransaction($cmsBlockGlossaryTransfer);
        });

        return $cmsBlockGlossaryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return void
     */
    protected function saveCmsGlossaryTransaction(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer)
    {
        foreach ($cmsBlockGlossaryTransfer->getGlossaryPlaceholders() as $glossaryPlaceholder) {
            $glossaryPlaceholder = $this->resolveTranslationKey($glossaryPlaceholder);

            $this->savePlaceholderTranslations($glossaryPlaceholder);

            $idCmsBlockGlossaryMapping = $this->saveCmsGlossaryKeyMapping($glossaryPlaceholder);
            $glossaryPlaceholder->setIdCmsBlockGlossaryKeyMapping($idCmsBlockGlossaryMapping);

            $this->touchActiveCmsBlock($glossaryPlaceholder->getFkCmsBlock());
        }
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    protected function touchActiveCmsBlock(int $idCmsBlock): void
    {
        if ($this->isCmsBlockActive($idCmsBlock)) {
            $this->touchFacade->touchActive(CmsBlockConfig::RESOURCE_TYPE_CMS_BLOCK, $idCmsBlock);
        }
    }

    /**
     * @param int $idCmsBlock
     *
     * @return bool
     */
    protected function isCmsBlockActive(int $idCmsBlock): bool
    {
        return $this->cmsBlockQueryContainer
            ->queryCmsBlockById($idCmsBlock)
            ->findOne()
            ->getIsActive();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer
     */
    protected function resolveTranslationKey(CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer)
    {
        if (!$glossaryPlaceholderTransfer->getTranslationKey()) {
            $translationKey = $this->cmsBlockGlossaryKeyGenerator->generateGlossaryKeyName(
                $glossaryPlaceholderTransfer->getFkCmsBlock(),
                $glossaryPlaceholderTransfer->getTemplateName(),
                $glossaryPlaceholderTransfer->getPlaceholder()
            );

            $glossaryPlaceholderTransfer->setTranslationKey($translationKey);
        }

        return $glossaryPlaceholderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return void
     */
    protected function savePlaceholderTranslations(CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer)
    {
        $translationKey = $glossaryPlaceholderTransfer->getTranslationKey();

        foreach ($glossaryPlaceholderTransfer->getTranslations() as $placeholderTranslationTransfer) {
            $this->setDefaultTranslation($placeholderTranslationTransfer);
            $keyTranslationTransfer = $this->createTranslationTransfer($placeholderTranslationTransfer, $translationKey);
            $this->glossaryFacade->saveGlossaryKeyTranslations($keyTranslationTransfer);
        }

        $glossaryKeyEntity = $this->findGlossaryKeyEntityByTranslationKey($translationKey);

        if ($glossaryKeyEntity === null) {
            return;
        }

        $glossaryPlaceholderTransfer->setFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey());
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer $placeholderTranslationTransfer
     *
     * @return void
     */
    protected function setDefaultTranslation(CmsBlockGlossaryPlaceholderTranslationTransfer $placeholderTranslationTransfer)
    {
        if ($placeholderTranslationTransfer->getTranslation() === null) {
            $placeholderTranslationTransfer->setTranslation(static::DEFAULT_TRANSLATION);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer $placeholderTranslationTransfer
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\KeyTranslationTransfer
     */
    protected function createTranslationTransfer(CmsBlockGlossaryPlaceholderTranslationTransfer $placeholderTranslationTransfer, $translationKey)
    {
        $keyTranslationTransfer = new KeyTranslationTransfer();
        $keyTranslationTransfer->setGlossaryKey($translationKey);

        $keyTranslationTransfer->setLocales([
            $placeholderTranslationTransfer->getLocaleName() => $placeholderTranslationTransfer->getTranslation(),
        ]);

        return $keyTranslationTransfer;
    }

    /**
     * @param string $translationKey
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKey
     */
    protected function findGlossaryKeyEntityByTranslationKey($translationKey)
    {
        return $this->glossaryQueryContainer
            ->queryKey($translationKey)
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return int
     */
    protected function saveCmsGlossaryKeyMapping(CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer)
    {
        if ($glossaryPlaceholderTransfer->getIdCmsBlockGlossaryKeyMapping() === null) {
            return $this->createPageKeyMapping($glossaryPlaceholderTransfer);
        } else {
            return $this->updatePageKeyMapping($glossaryPlaceholderTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return int
     */
    protected function createPageKeyMapping(CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer)
    {
        $this->assertPlaceholderNotAmbiguous(
            $glossaryPlaceholderTransfer->getFkCmsBlock(),
            $glossaryPlaceholderTransfer->getPlaceholder()
        );

        $cmsGlossaryKeyMappingEntity = new SpyCmsBlockGlossaryKeyMapping();
        $cmsGlossaryKeyMappingEntity->fromArray($glossaryPlaceholderTransfer->toArray());
        $cmsGlossaryKeyMappingEntity->save();

        return $cmsGlossaryKeyMappingEntity->getPrimaryKey();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return int
     */
    protected function updatePageKeyMapping(CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer)
    {
        $glossaryKeyMappingEntity = $this->getGlossaryKeyMappingById($glossaryPlaceholderTransfer->getIdCmsBlockGlossaryKeyMapping());
        $glossaryKeyMappingEntity->fromArray($glossaryPlaceholderTransfer->modifiedToArray());

        if (!$glossaryKeyMappingEntity->isModified()) {
            return $glossaryKeyMappingEntity->getPrimaryKey();
        }

        $isPlaceholderModified = $glossaryKeyMappingEntity->isColumnModified(SpyCmsBlockGlossaryKeyMappingTableMap::COL_PLACEHOLDER);
        $isIdCmsBlockModified = $glossaryKeyMappingEntity->isColumnModified(SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_CMS_BLOCK);

        if ($isPlaceholderModified || $isIdCmsBlockModified) {
            $this->assertPlaceholderNotAmbiguous(
                $glossaryPlaceholderTransfer->getFkCmsBlock(),
                $glossaryPlaceholderTransfer->getPlaceholder()
            );
        }

        $glossaryKeyMappingEntity->save();

        return $glossaryKeyMappingEntity->getPrimaryKey();
    }

    /**
     * @param int $idCmsBlock
     * @param string $placeholder
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\CmsBlockMappingAmbiguousException
     *
     * @return void
     */
    protected function assertPlaceholderNotAmbiguous($idCmsBlock, $placeholder)
    {
        $exists = $this->cmsBlockQueryContainer
            ->queryGlossaryKeyMappingByPlaceholdersAndIdCmsBlock([$placeholder], $idCmsBlock)
            ->exists();

        if ($exists) {
            throw new CmsBlockMappingAmbiguousException(sprintf('Tried to create an ambiguous mapping for placeholder %s on block %s', $placeholder, $idCmsBlock));
        }
    }

    /**
     * @param int $idGlossaryKeyMapping
     *
     * @throws \Spryker\Zed\CmsBlock\Business\Exception\MissingCmsBlockGlossaryKeyMapping
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping
     */
    protected function getGlossaryKeyMappingById($idGlossaryKeyMapping)
    {
        $mappingEntity = $this->findGlossaryKeyMappingEntityById($idGlossaryKeyMapping);

        if (!$mappingEntity) {
            throw new MissingCmsBlockGlossaryKeyMapping(
                sprintf('Tried to retrieve a missing glossary key mapping with id %s', $idGlossaryKeyMapping)
            );
        }

        return $mappingEntity;
    }

    /**
     * @param int $idGlossaryKeyMapping
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping
     */
    protected function findGlossaryKeyMappingEntityById($idGlossaryKeyMapping)
    {
        return $this->cmsBlockQueryContainer
            ->queryGlossaryKeyMappingById($idGlossaryKeyMapping)
            ->findOne();
    }

    /**
     * @param array $glossaryKeys
     *
     * @return void
     */
    protected function deleteGlossaryKeysTransaction(array $glossaryKeys)
    {
        $this->glossaryFacade->deleteTranslationsByFkKeys($glossaryKeys);
        $this->glossaryFacade->deleteKeys($glossaryKeys);
    }
}
