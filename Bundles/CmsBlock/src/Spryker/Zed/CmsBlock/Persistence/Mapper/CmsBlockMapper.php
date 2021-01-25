<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence\Mapper;

use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;

class CmsBlockMapper implements CmsBlockMapperInterface
{
    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $cmsBlockEntity): CmsBlockTransfer
    {
        return (new CmsBlockTransfer())->fromArray($cmsBlockEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityWithRelatedEntitiesToCmsBlockTransfer(
        SpyCmsBlock $cmsBlockEntity
    ): CmsBlockTransfer {
        $cmsBlockTransfer = $this->mapCmsBlockEntityToTransfer($cmsBlockEntity);

        $cmsBlockTemplateTransfer = $this->mapCmsBlockTemplateEntityToTransfer(
            $cmsBlockEntity->getCmsBlockTemplate(),
            new CmsBlockTemplateTransfer()
        );
        $cmsBlockTransfer->setCmsBlockTemplate($cmsBlockTemplateTransfer);

        $cmsBlockEntity->initSpyCmsBlockGlossaryKeyMappings(false);

        if (!$cmsBlockEntity->countSpyCmsBlockGlossaryKeyMappings()) {
            return $cmsBlockTransfer;
        }

        $cmsBlockGlossaryTransfer = new CmsBlockGlossaryTransfer();

        foreach ($cmsBlockEntity->getSpyCmsBlockGlossaryKeyMappings() as $cmsBlockGlossaryKeyMappingsEntity) {
            $cmsBlockGlossaryPlaceholderTransfer = $this->mapCmsBlockGlossaryKeyMappingsEntityToCmsBlockGlossaryPlaceholderTransfer(
                $cmsBlockGlossaryKeyMappingsEntity,
                new CmsBlockGlossaryPlaceholderTransfer()
            );

            $cmsBlockGlossaryTransfer->addGlossaryPlaceholder($cmsBlockGlossaryPlaceholderTransfer);
        }

        return $cmsBlockTransfer->setGlossary($cmsBlockGlossaryTransfer);
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping $cmsBlockGlossaryKeyMappingsEntity
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $cmsBlockGlossaryPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer
     */
    protected function mapCmsBlockGlossaryKeyMappingsEntityToCmsBlockGlossaryPlaceholderTransfer(
        SpyCmsBlockGlossaryKeyMapping $cmsBlockGlossaryKeyMappingsEntity,
        CmsBlockGlossaryPlaceholderTransfer $cmsBlockGlossaryPlaceholderTransfer
    ): CmsBlockGlossaryPlaceholderTransfer {
        $cmsBlockGlossaryPlaceholderTransfer->fromArray($cmsBlockGlossaryKeyMappingsEntity->toArray(), true);
        $glossaryKeyEntity = $cmsBlockGlossaryKeyMappingsEntity->getGlossaryKey();
        $glossaryKeyEntity->initSpyGlossaryTranslations(false);

        if (!$glossaryKeyEntity->countSpyGlossaryTranslations()) {
            return $cmsBlockGlossaryPlaceholderTransfer;
        }

        foreach ($glossaryKeyEntity->getSpyGlossaryTranslations() as $glossaryTranslationEntity) {
            $cmsBlockGlossaryPlaceholderTranslationTransfer = $this->mapGlossaryTranslationEntityToCmsBlockGlossaryPlaceholderTranslationTransfer(
                $glossaryTranslationEntity,
                new CmsBlockGlossaryPlaceholderTranslationTransfer()
            );

            $cmsBlockGlossaryPlaceholderTransfer->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer);
        }

        return $cmsBlockGlossaryPlaceholderTransfer;
    }

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $glossaryTranslationEntity
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer $cmsBlockGlossaryPlaceholderTranslationTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer
     */
    protected function mapGlossaryTranslationEntityToCmsBlockGlossaryPlaceholderTranslationTransfer(
        SpyGlossaryTranslation $glossaryTranslationEntity,
        CmsBlockGlossaryPlaceholderTranslationTransfer $cmsBlockGlossaryPlaceholderTranslationTransfer
    ): CmsBlockGlossaryPlaceholderTranslationTransfer {
        $cmsBlockGlossaryPlaceholderTranslationTransfer->fromArray($glossaryTranslationEntity->toArray(), true);

        return $cmsBlockGlossaryPlaceholderTranslationTransfer->setTranslation($glossaryTranslationEntity->getValue());
    }

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate $spyCmsBlockTemplate
     * @param \Generated\Shared\Transfer\CmsBlockTemplateTransfer $cmsBlockTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    protected function mapCmsBlockTemplateEntityToTransfer(
        SpyCmsBlockTemplate $spyCmsBlockTemplate,
        CmsBlockTemplateTransfer $cmsBlockTemplateTransfer
    ): CmsBlockTemplateTransfer {
        return $cmsBlockTemplateTransfer->fromArray($spyCmsBlockTemplate->toArray(), true);
    }
}
