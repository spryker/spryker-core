<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Dependency\Service;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;

class ConfigurableBundleCartToConfigurableBundleServiceBridge implements ConfigurableBundleCartToConfigurableBundleServiceInterface
{
    /**
     * @var \Spryker\Service\ConfigurableBundle\ConfigurableBundleServiceInterface
     */
    protected $configurableBundleService;

    /**
     * @param \Spryker\Service\ConfigurableBundle\ConfigurableBundleServiceInterface $configurableBundleService
     */
    public function __construct($configurableBundleService)
    {
        $this->configurableBundleService = $configurableBundleService;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    public function expandConfiguredBundleWithGroupKey(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer
    {
        return $this->configurableBundleService->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);
    }
}
