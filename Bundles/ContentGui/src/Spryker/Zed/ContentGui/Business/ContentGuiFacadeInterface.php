<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Business;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;

interface ContentGuiFacadeInterface
{
    /**
     * Specification:
     * - Converts html widgets to twig functions.
     * - Reutrns CmsGlossaryTransfer with translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertCmsGlossaryHtmlToTwigFunction(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer;

    /**
     * Specification:
     * - Converts html widgets to twig functions.
     * - Reutrns CmsBlockGlossaryTransfer with translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertCmsBlockGlossaryHtmlToTwigFunction(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer;

    /**
     * Specification:
     * - Converts twig functions to html widgets.
     * - Reutrns CmsGlossaryTransfer with translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function convertCmsGlossaryTwigFunctionToHtml(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer;

    /**
     * Specification:
     * - Converts twig functions to html widgets.
     * - Reutrns CmsBlockGlossaryTransfer with translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertCmsBlockGlossaryTwigFunctionToHtml(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer;
}
