<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Plugin\CmsGui;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryBeforeSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentGui\ContentGuiConfig getConfig()
 */
class HtmlToTwigFunctionCmsGlossaryBeforeSavePlugin extends AbstractPlugin implements CmsGlossaryBeforeSavePluginInterface
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
    public function execute(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->getFacade()->convertCmsGlossaryHtmlToTwigFunction($cmsGlossaryTransfer);
    }
}
