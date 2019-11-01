<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business;

use Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Generated\Shared\Transfer\FilterTransfer;

interface ConfigurableBundlePageSearchFacadeInterface
{
    /**
     * Specification:
     * - Publishes Configurable Bundle Templates to Search.
     *
     * @api
     *
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publishConfigurableBundleTemplates(array $configurableBundleTemplateIds): void;

    /**
     * Specification:
     * - Unpublishes Configurable Bundle Templates from Search.
     *
     * @api
     *
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function unpublishConfigurableBundleTemplates(array $configurableBundleTemplateIds): void;

    /**
     * Specification:
     * - Finds ConfigurableBundleTemplatePageSearch records by criteria from ConfigurableBundleTemplatePageSearchFilterTransfer.
     * - Returns ConfigurableBundleTemplatePageSearchCollectionTransfer with found records.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer
     */
    public function getConfigurableBundleTemplatePageSearchCollection(ConfigurableBundleTemplatePageSearchFilterTransfer $configurableBundleTemplatePageSearchFilterTransfer): ConfigurableBundleTemplatePageSearchCollectionTransfer;

    /**
     * Specification:
     * - Finds ConfigurableBundleTemplate records by criteria from FilterTransfer.
     * - ConfigurableBundleTemplateCollectionTransfer::configurableBundleTemplates are populated with idConfigurableBundleTemplate field only.
     * - Returns ConfigurableBundleTemplateCollectionTransfer with with found records.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateCollectionTransfer
     */
    public function getConfigurableBundleTemplateCollection(FilterTransfer $filterTransfer): ConfigurableBundleTemplateCollectionTransfer;
}
