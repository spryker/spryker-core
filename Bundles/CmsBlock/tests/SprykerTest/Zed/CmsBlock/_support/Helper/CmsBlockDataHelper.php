<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlock\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsBlockBuilder;
use Generated\Shared\DataBuilder\CmsBlockTemplateBuilder;
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

        $this->getCmsBlockFacade()->createCmsBlock($cmsBlockTransfer);

        return $cmsBlockTransfer;
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
