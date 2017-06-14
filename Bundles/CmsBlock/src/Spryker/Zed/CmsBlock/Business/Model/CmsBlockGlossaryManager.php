<?php


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
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToLocaleFacadeInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

class CmsBlockGlossaryManager implements CmsBlockGlossaryManagerInterface
{

    const CMS_PLACEHOLDER_PATTERN = '/<!-- CMS_PLACEHOLDER : "[a-zA-Z0-9_-]*" -->/';
    const CMS_PLACEHOLDER_VALUE_PATTERN = '/"([^"]+)"/';

    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var CmsBlockConfig
     */
    protected $config;

    /**
     * @var LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param CmsBlockConfig $cmsBlockConfig
     * @param CmsBlockToLocaleFacadeInterface $cmsBlockToLocaleFacade
     */
    public function __construct(
      CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
      CmsBlockConfig $cmsBlockConfig,
      CmsBlockToLocaleFacadeInterface $cmsBlockToLocaleFacade
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->config = $cmsBlockConfig;
        $this->localeFacade = $cmsBlockToLocaleFacade;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function findPlaceholders($idCmsBlock)
    {
        $spyCmsBlock = $this->getCmsBlockEntity($idCmsBlock);

        if ($spyCmsBlock === null) {
            return null;
        }

        $placeholders = $this->findPagePlaceholders($spyCmsBlock->getCmsBlockTemplate());

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
     * @param SpyCmsBlockTemplate $spyCmsBlockTemplate
     *
     * @return array
     */
    protected function findPagePlaceholders(SpyCmsBlockTemplate $spyCmsBlockTemplate)
    {
        $templateFiles = $this->config->getTemplateRealPaths($spyCmsBlockTemplate->getTemplatePath());

        foreach ($templateFiles as $templateFile) {
            if (file_exists($templateFile)) {
                return $this->getTemplatePlaceholders($templateFile);
            }
        }

        return [];
    }

    /**
     * @param string $templateFile
     *
     * @throws CmsBlockTemplateNotFoundException
     *
     * @return array
     */
    protected function getTemplatePlaceholders($templateFile)
    {
        if (!file_exists($templateFile)) {
            throw new CmsBlockTemplateNotFoundException(
                sprintf('Template file not found in "%s"', $templateFile)
            );
        }

        $templateContent = $this->readTemplateContents($templateFile);

        preg_match_all(static::CMS_PLACEHOLDER_PATTERN, $templateContent, $cmsPlaceholderLine);
        if (count($cmsPlaceholderLine) == 0) {
            return [];
        }

        preg_match_all(static::CMS_PLACEHOLDER_VALUE_PATTERN, implode(' ', $cmsPlaceholderLine[0]), $placeholderMap);

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
            ->findOne();
    }

    /**
     * @param int $idCmsBlock
     * @param array $placeholders
     *
     * @return array|\Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMapping[]
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
     * @return SpyCmsBlockGlossaryKeyMapping[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function getGlossaryMappingCollection($idCmsBlock, array $placeholders)
    {
        $glossaryKeyMappingCollection = $this->cmsBlockQueryContainer
            ->queryGlossaryKeyMappingByPlaceholdersAndIdCmsBlock($placeholders, $idCmsBlock)
            ->find();

        return $glossaryKeyMappingCollection;
    }


    /**
     * @param SpyCmsBlock $spyCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    protected function mapGlossaryTransfer(SpyCmsBlock $spyCmsBlock)
    {
        $glossaryTransfer = new CmsBlockGlossaryTransfer();
        $glossaryTransfer->fromArray($spyCmsBlock->toArray(), true);

        return $glossaryTransfer;
    }

    /**
     * @param SpyCmsBlock $spyCmsBlock
     * @param string $placeholder
     *
     * @return CmsBlockGlossaryPlaceholderTransfer
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
     * @param CmsBlockGlossaryPlaceholderTransfer $glossaryPlaceholderTransfer
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

            if (!isset($glossaryKeyEntityMap[$placeholder])) {
                $glossaryPlaceholderTransfer->addTranslation($translationTransfer);
                continue;
            }

            /** @var SpyCmsBlockGlossaryKeyMapping $spyCmsBlockGlossaryKeyMapping */
            $spyCmsBlockGlossaryKeyMapping = $glossaryKeyEntityMap[$placeholder];
            $glossaryPlaceholderTransfer->setIdCmsBlockGlossaryKeyMapping($spyCmsBlockGlossaryKeyMapping->getIdCmsBlockGlossaryKeyMapping());

            $this->setTranslationValue($spyCmsBlockGlossaryKeyMapping, $translationTransfer);

            $glossaryKeyEntity = $spyCmsBlockGlossaryKeyMapping->getGlossaryKey();
            $glossaryPlaceholderTransfer->setFkGlossaryKey($glossaryKeyEntity->getIdGlossaryKey());
            $glossaryPlaceholderTransfer->setTranslationKey($glossaryKeyEntity->getKey());
            $glossaryPlaceholderTransfer->addTranslation($translationTransfer);
        }
    }

    /**
     * @param SpyCmsBlockGlossaryKeyMapping $glossaryKeyMappingEntity
     * @param CmsBlockGlossaryPlaceholderTranslationTransfer $cmsPlaceholderTranslationTransfer
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
            if ($glossaryTranslationEntity->getFkLocale() !== $idLocale) {
                continue;
            }

            return $glossaryTranslationEntity->getValue();

        }

        return null;
    }

}