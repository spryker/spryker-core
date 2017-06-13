<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\KeyTranslationTransfer;
use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockMappingAmbiguousException;
use Spryker\Zed\CmsBlock\Business\Exception\MissingCmsBlockGlossaryKeyMapping;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryFacadeInterface;
use Spryker\Zed\CmsBlock\Dependency\QueryContainer\CmsBlockToGlossaryQueryContainerInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Spryker\Zed\Glossary\Business\GlossaryFacadeInterface;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockGlossaryWriter implements CmsBlockGlossaryWriterInterface
{

    const DEFAULT_TRANSLATION = '';

    use DatabaseTransactionHandlerTrait;

    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var CmsBlockGlossaryKeyGeneratorInterface
     */
    protected $cmsBlockGlossaryKeyGenerator;

    /**
     * @var CmsBlockToGlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @param CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param CmsBlockToGlossaryFacadeInterface $glossaryFacade
     * @param CmsBlockGlossaryKeyGeneratorInterface $cmsBlockGlossaryKeyGenerator
     * @param CmsBlockToGlossaryQueryContainerInterface $glossaryQueryContainer
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockToGlossaryFacadeInterface $glossaryFacade,
        CmsBlockGlossaryKeyGeneratorInterface $cmsBlockGlossaryKeyGenerator,
        CmsBlockToGlossaryQueryContainerInterface $glossaryQueryContainer
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->glossaryFacade = $glossaryFacade;
        $this->cmsBlockGlossaryKeyGenerator = $cmsBlockGlossaryKeyGenerator;
        $this->glossaryQueryContainer = $glossaryQueryContainer;
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
            ->select([SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY])
            ->find()
            ->getColumnValues(SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY);

        if (empty($glossaryKeys)) {
            return;
        }

        $this->glossaryFacade->deleteTranslationsByFkKeys($glossaryKeys);
        $this->glossaryFacade->deleteKeys($glossaryKeys);
    }

    /**
     * @param CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function saveGlossary(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer)
    {
        $this->handleDatabaseTransaction(function () use ($cmsBlockGlossaryTransfer) {
            return $this->saveCmsGlossaryTransaction($cmsBlockGlossaryTransfer);
        });

        return $cmsBlockGlossaryTransfer;
    }

    /**
     * @param CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     */
    protected function saveCmsGlossaryTransaction(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer)
    {
        foreach ($cmsBlockGlossaryTransfer->getGlossaryPlaceholders() as $glossaryPlaceholder) {
            $glossaryPlaceholder = $this->resolveTranslationKey($glossaryPlaceholder);

            $this->translatePlaceholder($glossaryPlaceholder);

            $idCmsBlockGlossaryMapping = $this->saveCmsGlossaryKeyMapping($glossaryPlaceholder);
            $glossaryPlaceholder->setIdCmsBlockGlossaryKeyMapping($idCmsBlockGlossaryMapping);
        }
    }

    /**
     * @param CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return CmsBlockGlossaryPlaceholderTransfer
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
     * @param CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
     *
     * @return void
     */
    protected function translatePlaceholder(CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer)
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
     * @param CmsBlockGlossaryPlaceholderTranslationTransfer $placeholderTranslationTransfer
     */
    protected function setDefaultTranslation(CmsBlockGlossaryPlaceholderTranslationTransfer $placeholderTranslationTransfer)
    {
        if ($placeholderTranslationTransfer->getTranslation() === null) {
            $placeholderTranslationTransfer->setTranslation(static::DEFAULT_TRANSLATION);
        }
    }

    /**
     * @param CmsBlockGlossaryPlaceholderTranslationTransfer $placeholderTranslationTransfer
     * @param string $translationKey
     *
     * @return KeyTranslationTransfer
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
     * @param CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
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
     * @param CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
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
     * @param CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
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
     * @throws CmsBlockMappingAmbiguousException
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
     * @param $idGlossaryKeyMapping
     *
     * @throws MissingCmsBlockGlossaryKeyMapping
     *
     * @return SpyCmsBlockGlossaryKeyMapping
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
     * @param $idGlossaryKeyMapping
     *
     * @return SpyCmsBlockGlossaryKeyMapping
     */
    protected function findGlossaryKeyMappingEntityById($idGlossaryKeyMapping)
    {
        return $this->cmsBlockQueryContainer
            ->queryGlossaryKeyMappingById($idGlossaryKeyMapping)
            ->findOne();
    }
}