<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfigurableBundleResponseTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ConfigurableBundleFacadeInterface
{
    /**
     * Specification:
     * - Persists configurable bundle template.
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Generates translation key using first translation.
     * - Persists provided configurable bundle template name translations.
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
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Updates translation key using first translation.
     * - Updates provided configurable bundle template name translations.
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
     * - Finds configurable bundle template in Persistence.
     * - Filters by configurable bundle template ID if provided.
     * - Expands found configurable bundle template with translations.
     * - Provides translations for locales specified in ConfigurableBundleTemplateFilterTransfer::translationLocales, or for all available locales otherwise.
     * - If single locale translation was requested but doesn't exist, fallback translation will be provided, or translation key if nothing found.
     * - Returns corresponding transfer object if found, null otherwise.
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
     * - Finds configurable bundle templates by criteria from ConfigurableBundleTemplateFilterTransfer.
     * - Expands found configurable bundle templates with translations.
     * - Provides translations for locales specified in ConfigurableBundleTemplateFilterTransfer::translationLocales, or for all available locales otherwise.
     * - Returns array of transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[]
     */
    public function getConfigurableBundleTemplateCollection(ConfigurableBundleTemplateFilterTransfer $configurableBundleTemplateFilterTransfer): array;

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
     * - Finds configurable bundle template slots which use given product list by ConfigurableBundleTemplateSlotFilterTransfer::productList::idProductList.
     * - Returns ProductListResponseTransfer with check results.
     * - ProductListResponseTransfer::isSuccessful is equal to true when usage cases were not found, false otherwise.
     * - ProductListResponseTransfer::messages contains usage details.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function checkProductListUsageAmongSlots(ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer): ProductListResponseTransfer;

    /**
     * Specification:
     * - Finds configurable bundle template slots by criteria from ConfigurableBundleTemplateSlotFilterTransfer.
     * - Expands found configurable bundle template slots with translations.
     * - Provides translations for locales specified in ConfigurableBundleTemplateSlotFilterTransfer::translationLocales, or for all available locales otherwise.
     * - Returns array of transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function getConfigurableBundleTemplateSlotCollection(ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer): array;

    /**
     * Specification:
     * - Persists configurable bundle template slot.
     * - Expects configurable bundle template ID to be provided.
     * - Creates new product list, assigns it to a slot.
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Generates translation key using first translation.
     * - Persists provided configurable bundle template slot name translations.
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

    /**
     * Specification:
     * - Expects product list ID to be provided.
     * - Persists configurable bundle template slot.
     * - Expects minimum one translation to be provided.
     * - Expects locale definition for each provided translation.
     * - Updates translation key using first translation.
     * - Persists provided configurable bundle template slot name translations.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleResponseTransfer
     */
    public function updateConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
    ): ConfigurableBundleResponseTransfer;

    /**
     * Specification:
     * - Removes configurable bundle template slot with given ID from Persistence.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplateSlot
     *
     * @return void
     */
    public function deleteConfigurableBundleTemplateSlotById(int $idConfigurableBundleTemplateSlot): void;

    /**
     * Specification:
     * - Finds configurable bundle template slot by criteria from ConfigurableBundleTemplateSlotFilterTransfer.
     * - Expands found configurable bundle template slots with translations.
     * - Provides translations for locales specified in ConfigurableBundleTemplateSlotFilterTransfer::translationLocales, or for all available locales otherwise.
     * - If single locale translation was requested but doesn't exist, fallback translation will be provided, or translation key if nothing found.
     * - Returns corresponding transfer object for the first matching record if found, null otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer|null
     */
    public function findConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ?ConfigurableBundleTemplateSlotTransfer;

    /**
     * Specification:
     * - Filters configurable bundle template slot records by ID.
     * - Returns ID of a product list assigned to a slot.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return int
     */
    public function getProductListIdByIdConfigurableBundleTemplate(int $idConfigurableBundleTemplate): int;
}
