<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ConfigurableBundle;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;

interface ConfigurableBundleServiceInterface
{
    /**
     * Specification:
     * - Requires `ConfiguredBundleTransfer.template` to be set.
     * - Requires `ConfiguredBundleTransfer.template.uuid` to be set.
     * - Generates the group key based on `ConfiguredBundleTransfer.template.uuid`.
     * - Returns `ConfiguredBundleTransfer` expanded with the group key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    public function expandConfiguredBundleWithGroupKey(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer;
}
