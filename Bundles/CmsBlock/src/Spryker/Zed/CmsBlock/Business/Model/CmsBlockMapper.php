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
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;

class CmsBlockMapper implements CmsBlockMapperInterface
{
    /**
     * @var \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReaderInterface
     */
    protected $cmsBlockStoreRelationReader;

    /**
     * @param \Spryker\Zed\CmsBlock\Business\Model\CmsBlockStoreRelationReaderInterface $cmsBlockStoreRelationReader
     */
    public function __construct(CmsBlockStoreRelationReaderInterface $cmsBlockStoreRelationReader)
    {
        $this->cmsBlockStoreRelationReader = $cmsBlockStoreRelationReader;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $spyCmsBlock)
    {
        $cmsBlockTransfer = new CmsBlockTransfer();
        $cmsBlockTransfer->fromArray($spyCmsBlock->toArray(), true);
        $cmsBlockTransfer->setTemplateName($spyCmsBlock->getCmsBlockTemplate()->getTemplateName());

        $cmsBlockGlossaryTransfer = $this->createGlossaryTransfer($spyCmsBlock);
        $cmsBlockTransfer->setGlossary($cmsBlockGlossaryTransfer);
        $cmsBlockTransfer->setStoreRelation(
            $this->getStoreRelation($spyCmsBlock->getIdCmsBlock())
        );

        return $cmsBlockTransfer;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function getStoreRelation($idCmsBlock)
    {
        return $this->cmsBlockStoreRelationReader->getStoreRelation(
            (new StoreRelationTransfer())
                ->setIdEntity($idCmsBlock)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock
     */
    public function mapCmsBlockTransferToEntity(CmsBlockTransfer $cmsBlockTransfer, SpyCmsBlock $spyCmsBlock)
    {
        $spyCmsBlock->fromArray($cmsBlockTransfer->toArray());

        return $spyCmsBlock;
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
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
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $spyCmsBlock
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping $spyCmsGlossaryKeyMapping
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer
     */
    protected function createGlossaryPlaceholderTransfer(SpyCmsBlock $spyCmsBlock, SpyCmsBlockGlossaryKeyMapping $spyCmsGlossaryKeyMapping)
    {
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
