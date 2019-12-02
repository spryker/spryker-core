<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;

interface ConfigurableBundleTemplateSlotEditFormDataProviderExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands form options for ConfigurableBundleTemplateSlotEditForm with additional data.
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandOptions(array $options): array;

    /**
     * Specification:
     * - Expands form data for ConfigurableBundleTemplateSlotEditForm with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer
     */
    public function expandData(ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer): ConfigurableBundleTemplateSlotEditFormTransfer;
}
