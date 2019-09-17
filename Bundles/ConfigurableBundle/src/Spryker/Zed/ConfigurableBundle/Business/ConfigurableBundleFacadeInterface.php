<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business;

use Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer;
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
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function createConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Persists configurable bundle template.
     * - Updates configurable bundle template name translations if provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateResponseTransfer
     */
    public function updateConfigurableBundleTemplate(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
    ): ConfigurableBundleTemplateResponseTransfer;

    /**
     * Specification:
     * - Finds configurable bundle template by id.
     * - Returns corresponding transfer object if found, null otherwise.
     *
     * @api
     *
     * @param int $idConfigurableBundleTemplate
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer|null
     */
    public function findConfigurableBundleTemplateById(int $idConfigurableBundleTemplate): ?ConfigurableBundleTemplateTransfer;

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
}
