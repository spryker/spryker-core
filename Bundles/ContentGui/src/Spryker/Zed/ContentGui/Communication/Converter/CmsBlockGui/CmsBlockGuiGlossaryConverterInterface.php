<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Converter\CmsBlockGui;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;

interface CmsBlockGuiGlossaryConverterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertTwigFunctionToHtml(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer;

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function convertHtmlToTwigFunction(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer;
}
