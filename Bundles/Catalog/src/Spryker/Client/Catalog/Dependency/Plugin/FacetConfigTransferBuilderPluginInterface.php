<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog\Dependency\Plugin;

interface FacetConfigTransferBuilderPluginInterface
{
    /**
     * Specification:
     * - Builds a facet filter configuration transfer for the catalog page.
     * - The plugins can be activated by adding them to CatalogDependencyProvider::getFacetConfigTransferBuilderPlugins().
     * - The displayed facet filters and their order for the catalog page depends on the active plugins.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function build();
}
