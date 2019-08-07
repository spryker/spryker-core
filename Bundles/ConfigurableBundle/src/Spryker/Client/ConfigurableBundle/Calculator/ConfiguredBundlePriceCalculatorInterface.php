<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle\Calculator;

use Generated\Shared\Transfer\ConfiguredBundlePriceTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;

interface ConfiguredBundlePriceCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundlePriceTransfer
     */
    public function calculateConfiguredBundlePrice(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundlePriceTransfer;
}
