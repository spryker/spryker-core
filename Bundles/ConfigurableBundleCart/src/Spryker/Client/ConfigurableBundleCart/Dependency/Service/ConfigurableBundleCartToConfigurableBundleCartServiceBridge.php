<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Dependency\Service;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;

class ConfigurableBundleCartToConfigurableBundleCartServiceBridge implements ConfigurableBundleCartToConfigurableBundleCartServiceInterface
{
    /**
     * @var \Spryker\Service\ConfigurableBundleCart\ConfigurableBundleCartServiceInterface
     */
    protected $configurableBundleCartService;

    /**
     * @param \Spryker\Service\ConfigurableBundleCart\ConfigurableBundleCartServiceInterface $configurableBundleCartService
     */
    public function __construct($configurableBundleCartService)
    {
        $this->configurableBundleCartService = $configurableBundleCartService;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    public function expandConfiguredBundleWithGroupKey(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer
    {
        return $this->configurableBundleCartService->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);
    }
}
