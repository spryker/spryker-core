<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlock\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsBlockBuilder;
use Generated\Shared\DataBuilder\CmsBlockTemplateBuilder;
use Generated\Shared\Transfer\CmsBlockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CmsBlockDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function haveCmsBlock(array $seedData = [])
    {
        $cmsBlockTemplateTransfer = $this->haveCmsBlockTemplate();

        $cmsBlockTransfer = (new CmsBlockBuilder($seedData))->build();
        $cmsBlockTransfer->setIdCmsBlock(null);
        $cmsBlockTransfer->setFkTemplate($cmsBlockTemplateTransfer->getIdCmsBlockTemplate());
        $this->setStoreRelation($cmsBlockTransfer, $seedData);

        $this->getCmsBlockFacade()->createCmsBlock($cmsBlockTransfer);

        return $cmsBlockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $seedData
     *
     * @return void
     */
    protected function setStoreRelation(CmsBlockTransfer $cmsBlockTransfer, array $seedData = [])
    {
        if (!isset($seedData[CmsBlockTransfer::STORE_RELATION])) {
            return;
        }

        $cmsBlockTransfer->setStoreRelation(
            (new StoreRelationTransfer())
                ->fromArray($seedData[CmsBlockTransfer::STORE_RELATION])
        );
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsBlockTemplateTransfer
     */
    public function haveCmsBlockTemplate(array $seedData = [])
    {
        $cmsBlockTemplateTransfer = (new CmsBlockTemplateBuilder($seedData))->build();
        $cmsBlockTemplateTransfer->setIdCmsBlockTemplate(null);

        $this->getCmsBlockFacade()
            ->createTemplate($cmsBlockTemplateTransfer->getTemplateName(), $cmsBlockTemplateTransfer->getTemplatePath());

        $cmsBlockTemplateTransfer = $this->getCmsBlockFacade()
            ->findTemplate($cmsBlockTemplateTransfer->getTemplatePath());

        return $cmsBlockTemplateTransfer;
    }

    /**
     * @return \Spryker\Zed\CmsBlock\Business\CmsBlockFacadeInterface
     */
    protected function getCmsBlockFacade()
    {
        return $this->getLocator()->cmsBlock()->facade();
    }
}
