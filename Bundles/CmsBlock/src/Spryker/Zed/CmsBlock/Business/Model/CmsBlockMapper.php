<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;

class CmsBlockMapper implements CmsBlockMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapperInterface
     */
    protected $cmsBlockStoreRelationMapper;

    /**
     * @param \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationMapperInterface $cmsBlockStoreRelationMapper
     */
    public function __construct(CmsBlockStoreRelationMapperInterface $cmsBlockStoreRelationMapper)
    {
        $this->cmsBlockStoreRelationMapper = $cmsBlockStoreRelationMapper;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $spyCmsBlock): CmsBlockTransfer
    {
        $cmsBlockTransfer = new CmsBlockTransfer();
        $cmsBlockTransfer->fromArray($spyCmsBlock->toArray(), true);
        $cmsBlockTransfer->setTemplateName($spyCmsBlock->getCmsBlockTemplate()->getTemplateName());
        $cmsBlockTransfer->setStoreRelation(
            $this->cmsBlockStoreRelationMapper->mapStoreRelationToTransfer($spyCmsBlock)
        );

        $cmsBlockGlossaryTransfer = $this->createGlossaryTransfer($spyCmsBlock);
        $cmsBlockTransfer->setGlossary($cmsBlockGlossaryTransfer);

        return $cmsBlockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock
     */
    public function mapCmsBlockTransferToEntity(CmsBlockTransfer $cmsBlockTransfer, SpyCmsBlock $spyCmsBlock): SpyCmsBlock
    {
        $spyCmsBlock->fromArray($cmsBlockTransfer->toArray());

        return $spyCmsBlock;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    protected function createGlossaryTransfer(SpyCmsBlock $spyCmsBlock): CmsBlockGlossaryTransfer
    {
        $cmsBlockGlossary = new CmsBlockGlossaryTransfer();

        foreach ($spyCmsBlock->getSpyCmsBlockGlossaryKeyMappingsJoinGlossaryKey() as $spyCmsGlossaryKeyMapping) {
            $cmsBlockGlossaryPlaceholder = $this->createGlossaryPlaceholderTransfer($spyCmsBlock, $spyCmsGlossaryKeyMapping);
            $cmsBlockGlossary->addGlossaryPlaceholder($cmsBlockGlossaryPlaceholder);
        }

        return $cmsBlockGlossary;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping $spyCmsGlossaryKeyMapping
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer
     */
    protected function createGlossaryPlaceholderTransfer(
        SpyCmsBlock $spyCmsBlock,
        SpyCmsBlockGlossaryKeyMapping $spyCmsGlossaryKeyMapping
    ): CmsBlockGlossaryPlaceholderTransfer {
        $placeholderTransfer = new CmsBlockGlossaryPlaceholderTransfer();

        $spyGlossaryKey = $spyCmsGlossaryKeyMapping->getGlossaryKey();
        $placeholderTransfer
            ->setPlaceholder($spyCmsGlossaryKeyMapping->getPlaceholder())
            ->setTranslationKey($spyGlossaryKey->getKey())
            ->setFkCmsBlock($spyCmsBlock->getIdCmsBlock())
            ->setIdCmsBlockGlossaryKeyMapping($spyCmsGlossaryKeyMapping->getIdCmsBlockGlossaryKeyMapping())
            ->setFkGlossaryKey($spyGlossaryKey->getIdGlossaryKey())
            ->setTemplateName($spyCmsBlock->getCmsBlockTemplate()->getTemplateName());

        foreach ($spyGlossaryKey->getSpyGlossaryTranslations() as $spyGlossaryTranslation) {
            $translationTransfer = new CmsBlockGlossaryPlaceholderTranslationTransfer();
            $translationTransfer
                ->setFkLocale($spyGlossaryTranslation->getFkLocale())
                ->setLocaleName($spyGlossaryTranslation->getLocale()->getLocaleName())
                ->setTranslation($spyGlossaryTranslation->getValue());
        }

        return $placeholderTransfer;
    }
}
