<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Plugin\CmsBlockGui;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin\CmsBlockGlossaryBeforeSavePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentGui\ContentGuiConfig getConfig()
 */
class HtmlToTwigExpressionsCmsBlockGlossaryBeforeSavePlugin extends AbstractPlugin implements CmsBlockGlossaryBeforeSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes before saving CmsBlockGlossaryTransfer data to the database.
     * - Converts content item html editor widgets to twig twig expressions in CmsBlockGlossaryPlaceholderTranslationTransfer translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function execute(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer
    {
        return $this->getFacade()->convertCmsBlockGlossaryHtmlToTwigExpressions($cmsBlockGlossaryTransfer);
    }
}
