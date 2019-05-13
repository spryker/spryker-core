<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiBusinessFactory getFactory()
 */
class ContentGuiFacade extends AbstractFacade implements ContentGuiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertCmsGlossaryHtmlToTwigFunction(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->getFactory()->createCmsGuiGlossaryConverter()->convertHtmlToTwigFunction($cmsGlossaryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertCmsBlockGlossaryHtmlToTwigFunction(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        return $this->getFactory()->createCmsBlockGuiGlossaryConverter()->convertHtmlToTwigFunction($cmsBlockGlossaryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertCmsGlossaryTwigFunctionToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->getFactory()->createCmsGuiGlossaryConverter()->convertTwigFunctionToHtml($cmsGlossaryTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertCmsBlockGlossaryTwigFunctionToHtml(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        return $this->getFactory()->createCmsBlockGuiGlossaryConverter()->convertTwigFunctionToHtml($cmsBlockGlossaryTransfer);
    }
}
