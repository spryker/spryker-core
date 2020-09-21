<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SalesProductConfigurationTemplateTransfer;

/**
 * Use this plugin to provide the template and data for product configuration display.
 */
interface ProductConfigurationRenderPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable for a product configuration item.
     * - Hint: mostly the check should be done by the configuratorKey of the configuration instance.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isApplicable(ItemTransfer $itemTransfer): bool;

    /**
     * Specification:
     *  - Returns template to be rendered.
     *  - It includes data to be used for the rendering.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\SalesProductConfigurationTemplateTransfer
     */
    public function getTemplate(ItemTransfer $itemTransfer): SalesProductConfigurationTemplateTransfer;
}
