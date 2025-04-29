<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SspDashboardManagement\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsBlockBuilder;
use Generated\Shared\DataBuilder\CmsBlockGlossaryPlaceholderBuilder;
use Generated\Shared\DataBuilder\CmsBlockGlossaryPlaceholderTranslationBuilder;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class SspDashboardManagementHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     * @param int|null $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function haveSalesRepresentativeCmsBlockForBusinessUnit(array $seedData = [], ?int $idCompanyBusinessUnit = 0): CmsBlockGlossaryTransfer
    {
        $cmsBlockTemplateTransfer = $this->getCmsBlockFacade()->findTemplate('@CmsBlock/template/title_and_content_block.twig');

        $cmsBlockTransfer = (new CmsBlockBuilder($seedData))->build();
        $this->setStoreRelation($cmsBlockTransfer, $seedData);
        $blockName = $cmsBlockTransfer->getName() . $idCompanyBusinessUnit;
        $cmsBlockTransfer->setName($blockName)
            ->setKey($blockName)
            ->setIdCmsBlock(null)
            ->setFkTemplate($cmsBlockTemplateTransfer->getIdCmsBlockTemplate())
            ->setTemplateName($cmsBlockTemplateTransfer->getTemplateName());

        $cmsBlockTransfer = $this->getCmsBlockFacade()->createCmsBlock($cmsBlockTransfer);

        $this->createTranslations($cmsBlockTransfer);

        return $this->getCmsBlockFacade()->findGlossary($cmsBlockTransfer->getIdCmsBlockOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $seedData
     *
     * @return void
     */
    protected function setStoreRelation(CmsBlockTransfer $cmsBlockTransfer, array $seedData = []): void
    {
        if (!isset($seedData[CmsBlockTransfer::STORE_RELATION])) {
            return;
        }

        $cmsBlockTransfer->setStoreRelation(
            (new StoreRelationTransfer())
                ->fromArray($seedData[CmsBlockTransfer::STORE_RELATION]),
        );
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected function getCmsBlockFacade(): CmsBlockFacadeInterface
    {
        return $this->getLocator()->cmsBlock()->facade();
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function createTranslations(CmsBlockTransfer $cmsBlockTransfer): void
    {
        $cmsBlockGlossaryPlaceholderTranslationTransfer = (new CmsBlockGlossaryPlaceholderTranslationBuilder())
            ->build()
            ->setFkLocale($cmsBlockTransfer->getLocale()->getIdLocale())
            ->setLocaleName($cmsBlockTransfer->getLocale()->getLocaleName());

        $contentCmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderBuilder())
            ->build()
            ->setPlaceholder('content')
            ->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer)
            ->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->setTemplateName($cmsBlockTransfer->getTemplateName());
        $descriptionCmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderBuilder())
            ->build()
            ->setPlaceholder('description')
            ->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer)
            ->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->setTemplateName($cmsBlockTransfer->getTemplateName());
        $titleCmsBlockGlossaryPlaceholderTransfer = (new CmsBlockGlossaryPlaceholderBuilder())
            ->build()
            ->setPlaceholder('title')
            ->addTranslation($cmsBlockGlossaryPlaceholderTranslationTransfer)
            ->setFkCmsBlock($cmsBlockTransfer->getIdCmsBlock())
            ->setTemplateName($cmsBlockTransfer->getTemplateName());

        $cmsBlockGlossaryTransfer = (new CmsBlockGlossaryTransfer())
            ->addGlossaryPlaceholder($contentCmsBlockGlossaryPlaceholderTransfer)
            ->addGlossaryPlaceholder($descriptionCmsBlockGlossaryPlaceholderTransfer)
            ->addGlossaryPlaceholder($titleCmsBlockGlossaryPlaceholderTransfer);

        $this->getCmsBlockFacade()->saveGlossary($cmsBlockGlossaryTransfer);
    }
}
