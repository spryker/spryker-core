<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;

interface CmsBlockGlossaryAfterFindPluginInterface
{
    /**
     * Specification:
     * - Modifies/expands CmsBlockGlossaryTransfer after glossary was found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function execute(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer): CmsBlockGlossaryTransfer;
}
