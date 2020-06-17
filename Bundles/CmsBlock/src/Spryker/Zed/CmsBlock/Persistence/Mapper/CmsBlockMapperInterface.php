<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence\Mapper;

use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer;
use Generated\Shared\Transfer\CmsBlockTemplateTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation;

interface CmsBlockMapperInterface
{
    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityToTransfer(SpyCmsBlock $cmsBlockEntity): CmsBlockTransfer;

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate $spyCmsBlockTemplate
     * @param \Generated\Shared\Transfer\CmsBlockTemplateTransfer $cmsBlockTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function mapCmsBlockTemplateEntityToTransfer(
        SpyCmsBlockTemplate $spyCmsBlockTemplate,
        CmsBlockTemplateTransfer $cmsBlockTemplateTransfer
    ): CmsBlockTemplateTransfer;

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function mapCmsBlockEntityWithRelatedEntitiesToCmsBlockTransfer(
        SpyCmsBlock $cmsBlockEntity
    ): CmsBlockTransfer;

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockGlossaryKeyMapping $cmsBlockGlossaryKeyMappingsEntity
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer $cmsBlockGlossaryPlaceholderTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer
     */
    public function mapCmsBlockGlossaryKeyMappingsEntityToCmsBlockGlossaryPlaceholderTransfer(
        SpyCmsBlockGlossaryKeyMapping $cmsBlockGlossaryKeyMappingsEntity,
        CmsBlockGlossaryPlaceholderTransfer $cmsBlockGlossaryPlaceholderTransfer
    ): CmsBlockGlossaryPlaceholderTransfer;

    /**
     * @param \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslation $glossaryTranslationEntity
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer $cmsBlockGlossaryPlaceholderTranslationTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTranslationTransfer
     */
    public function mapGlossaryTranslationEntityToCmsBlockGlossaryPlaceholderTranslationTransfer(
        SpyGlossaryTranslation $glossaryTranslationEntity,
        CmsBlockGlossaryPlaceholderTranslationTransfer $cmsBlockGlossaryPlaceholderTranslationTransfer
    ): CmsBlockGlossaryPlaceholderTranslationTransfer;
}
