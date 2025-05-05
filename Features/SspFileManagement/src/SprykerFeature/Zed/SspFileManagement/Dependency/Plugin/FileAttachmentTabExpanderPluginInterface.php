<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Dependency\Plugin;

use Generated\Shared\Transfer\TabsViewTransfer;

interface FileAttachmentTabExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the file attachment tabs with additional tabs.
     * - Adds tabs to the TabsViewTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expandTabs(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer;
}
