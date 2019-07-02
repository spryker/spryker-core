<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CmsGlossaryTransfer;

interface CmsGlossaryBeforeSavePluginInterface
{
    /**
     * Specification:
     * - Modifies/expands CmsGlossaryTransfer before glossary will be saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function execute(CmsGlossaryTransfer $cmsGlossaryTransfer): CmsGlossaryTransfer;
}
