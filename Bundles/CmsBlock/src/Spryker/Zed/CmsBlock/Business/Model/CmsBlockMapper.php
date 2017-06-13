<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKey;

class CmsBlockMapper implements CmsBlockMapperInterface
{
    /**
     * @param SpyCmsBlock $spyCmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $spyCmsBlock)
    {
        $cmsBlockTransfer = new CmsBlockTransfer();
        $cmsBlockTransfer->fromArray($spyCmsBlock->toArray(), true);
        $cmsBlockTransfer->setTemplateName($spyCmsBlock->getCmsBlockTemplate()->getTemplateName());

        $cmsBlockGlossaryTransfer = $this->createGlossaryTransfer($spyCmsBlock);
        $cmsBlockTransfer->setGlossary($cmsBlockGlossaryTransfer);

        return $cmsBlockTransfer;
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     * @param SpyCmsBlock $spyCmsBlock
     *
     * @return SpyCmsBlock
     */
    public function mapCmsBlockTransferToEntity(CmsBlockTransfer $cmsBlockTransfer, SpyCmsBlock $spyCmsBlock)
    {
        $spyCmsBlock->fromArray($cmsBlockTransfer->toArray());

        return $spyCmsBlock;
    }

    /**
     * @param SpyCmsBlock $spyCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    protected function createGlossaryTransfer(SpyCmsBlock $spyCmsBlock)
    {
        $cmsBlockGlossary = new CmsBlockGlossaryTransfer();

        foreach ($spyCmsBlock->getSpyCmsBlockGlossaryKeyMappingsJoinGlossaryKey() as $spyCmsGlossaryKeyMapping) {
            $cmsBlockGlossaryPlaceholder = $this->createGlossaryPlaceholderTransfer($spyCmsBlock, $spyCmsGlossaryKeyMapping);
            $cmsBlockGlossary->addGlossaryPlaceholder($cmsBlockGlossaryPlaceholder);
        }

        return $cmsBlockGlossary;
    }

    /**
     * @param SpyCmsBlock $spyCmsBlock
     * @param SpyCmsBlockGlossaryKeyMapping $spyCmsGlossaryKeyMapping
     *
     * @return CmsBlockGlossaryPlaceholderTransfer
     */
    protected function createGlossaryPlaceholderTransfer(SpyCmsBlock $spyCmsBlock, SpyCmsBlockGlossaryKeyMapping $spyCmsGlossaryKeyMapping)
    {
        $placeholderTransfer = new CmsBlockGlossaryPlaceholderTransfer();

        $spyGlossaryKey = $spyCmsGlossaryKeyMapping->getGlossaryKey();
        $placeholderTransfer->setPlaceholder($spyCmsGlossaryKeyMapping->getPlaceholder());
        $placeholderTransfer->setTranslationKey($spyGlossaryKey->getKey());
        $placeholderTransfer->setFkCmsBlock($spyCmsBlock->getIdCmsBlock());
        $placeholderTransfer->setIdCmsBlockGlossaryKeyMapping($spyCmsGlossaryKeyMapping->getIdCmsBlockGlossaryKeyMapping());
        $placeholderTransfer->setFkGlossaryKey($spyGlossaryKey->getIdGlossaryKey());
        $placeholderTransfer->setTemplateName($spyCmsBlock->getCmsBlockTemplate()->getTemplateName());

        foreach ($spyGlossaryKey->getSpyGlossaryTranslations() as $spyGlossaryTranslation) {
            $translationTransfer = new CmsBlockGlossaryPlaceholderTranslationTransfer();
            $translationTransfer->setFkLocale($spyGlossaryTranslation->getFkLocale());
            $translationTransfer->setLocaleName($spyGlossaryTranslation->getLocale()->getLocaleName());
            $translationTransfer->setTranslation($spyGlossaryTranslation->getValue());
        }

        return $placeholderTransfer;
    }
}