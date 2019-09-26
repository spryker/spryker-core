<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfigurableBundleResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ConfigurableBundleFacadeInterface
{
    /**
     * Specification:
     * - Persists configurable bundle template.
     * - Generates translation key and persists configurable bundle template name translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer;

    /**
     * Specification:
     * - Persists configurable bundle template.
     * - Updates configurable bundle template name translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleResponseTransfer;

    /**
     * Specification:
     * - Finds configurable bundle template by criteria from ConfigurableBundleTemplateFilterTransfer.
     * - Returns corresponding transfer object for the first matching record if found, null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplate(
        ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
    ): ?ConfigurableBundleTemplateTransfer;

    /**
     * Specification:
     * - Retrieves configurable bundle template by id.
     * - Removes configurable bundle template from Persistence.
     * - Removes configurable bundle template slots from Persistence.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void;

    /**
     * Specification:
     * - Removes item from QuoteTransfer if its configurable bundle template is removed.
     * - Removes item from QuoteTransfer if its configurable bundle template is inactive.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Updates isActive configurable bundle template property property to true in Persistence.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function activateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void;

    /**
     * Specification:
     * - Updates isActive configurable bundle template property to false in Persistence.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return void
     */
    public function deactivateConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): void;

    /**
     * Specification:
     * - Persists configurable bundle template slot.
     * - Generates translation key and persists configurable bundle template name translations.
     * - Creates new product list, assigns it to a slot.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function createConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer;
}
