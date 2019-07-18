<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Plugin\CmsGui;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\CmsGuiExtension\Dependency\Plugin\CmsGlossaryAfterFindPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentGui\ContentGuiConfig getConfig()
 */
class TwigExpressionsToHtmlCmsGlossaryAfterFindPlugin extends AbstractPlugin implements CmsGlossaryAfterFindPluginInterface
{
    /**
     * {@inheritDoc}
     * - Executes after finding CmsGlossaryTransfer data in the database.
     * - Converts twig twig expressions to content item html editor widgets in CmsPlaceholderTranslationTransfer translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function execute(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer
    {
        return $this->getFacade()->convertCmsGlossaryTwigExpressionsToHtml($cmsGlossaryTransfer);
    }
}
