<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Dependency\Plugin;

interface SortConfigTransferBuilderPluginInterface
{
    /**
     * Specification:
     * - Builds a sort configuration transfer for the catalog page.
     * - The plugins can be activated by adding them to CatalogDependencyProvider::getSortConfigTransferBuilderPlugins().
     * - The displayed sort fields and their order for the catalog page depends on the active plugins.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SortConfigTransfer
     */
    public function build();
}
