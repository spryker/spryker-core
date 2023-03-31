<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StoreTransfer;

/**
 * Expands store view page with additional info blocks.
 *
 * Use this plugin to extend page with store view.
 */
interface StoreViewExpanderPluginInterface
{
    /**
     * Specification:
     * - Returns template path to expand store view page with.
     *
     * @api
     *
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * Specification:
     * - Returns store data assigned to expanded template.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function getTemplateData(StoreTransfer $storeTransfer): array;
}
