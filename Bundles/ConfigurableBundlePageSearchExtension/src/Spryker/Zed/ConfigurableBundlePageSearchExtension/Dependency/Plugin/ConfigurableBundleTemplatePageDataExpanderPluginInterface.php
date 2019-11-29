<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

interface ConfigurableBundleTemplatePageDataExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands the provided ConfigurableBundleTemplatePageSearchTransfer object and returns the modified version.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    public function expand(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer;
}
