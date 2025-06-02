<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\LinkTransfer;

interface NavigationPluginInterface
{
    /**
     * Specification:
     * - This method is used to create a navigation item.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\LinkTransfer|null
     */
    public function getNavigationItem(): ?LinkTransfer;
}
